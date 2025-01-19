<?php

namespace App\Livewire\Client;

use Carbon\Carbon;
use App\Models\Plan;
use Livewire\Component;
use App\Models\Instance;
use App\Models\Entreprise;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Subscription;
use Livewire\WithPagination;
use App\Services\CreateUsersInnov;
use App\Services\DolibarrService;
use App\Models\DolibarrCredential;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\InstanceCreated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use App\Services\InstanceProvisioningService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateInstances extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    // Propriétés de base
    public $newInstanceInfo = null;
    public $name = '';
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;

    // Propriétés pour la progression
    public $isCreating = false;
    public $progress = 0;
    public $currentStep = '';
    public $errorMessage = '';

    protected $listeners = [
        'progressUpdate' => 'updateProgress',
        'instanceCreated' => 'handleInstanceCreated',
        'instanceError' => 'handleInstanceError'
    ];

    public function updateProgress($step)
    {
        $progressSteps = [
            'validation' => 10,
            'init' => 20,
            'provision' => 40,
            'database' => 60,
            'users' => 80,
            'complete' => 100
        ];

        if (isset($progressSteps[$step])) {
            $this->progress = $progressSteps[$step];
            $this->currentStep = $step;
        }
    }

    public function updatedEntrepriseId($value)
    {
        if ($value) {
            $entreprise = Entreprise::find($value);
            if ($entreprise) {
                $this->selectedPays = $entreprise->pays;
            }
        } else {
            $this->selectedPays = null;
        }
    }

    public function loadEnterprises()
    {
        $this->entreprises = Cache::remember('user_entreprises_' . Auth::id(), 300, function () {
            return Auth::user()->entreprises;
        });
    }

    public function mount()
    {
        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
        }
    }

    public function checkInstanceCreationEligibility()
    {
        $user = Auth::user();
        $this->showPlanSelection = !$user->canCreateInstance();
    }

    #[On('planChanged')]
    public function planChanged()
    {
        $this->checkInstanceCreationEligibility();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'unique:instances,name', 'min:3', 'max:15'],
            'entreprise_id' => 'required|exists:entreprises,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom de l\'instance est obligatoire.',
        'name.unique' => 'Ce nom d\'instance est déjà utilisé.',
        'name.min' => 'Le nom de l\'instance doit contenir au moins 3 caractères.',
        'name.max' => 'Le nom de l\'instance ne peut pas dépasser 15 caractères.',
        'entreprise_id.required' => 'Le choix de l\'entreprise est obligatoire.',
        'entreprise_id.exists' => 'L\'entreprise sélectionnée n\'existe pas.',
    ];

    public function store()
    {
        try {
            $this->isCreating = true;
            $this->progress = 0;
            $this->errorMessage = '';
            $this->updateProgress('validation');

            $this->validate();

            $user = Auth::user();
            if (!$user->canCreateInstance()) {
                throw new \Exception('Vous avez atteint votre limite de création d\'instances.');
            }

            $this->updateProgress('init');

            // Préparation des données
            $instanceData = $this->prepareInstanceData($user);

            $this->updateProgress('provision');

            // Service de provisionnement
            $provisioningService = app(InstanceProvisioningService::class);
            $instanceDetails = $provisioningService->provisionInstance(
                $instanceData['name'],
                $instanceData['password'],
                $instanceData['login'],
                $instanceData['url_suffix'],
                $instanceData['api_key'],
                $user->email
            );

            if (!$instanceDetails) {
                throw new \Exception('Erreur lors de la création de l\'instance.');
            }

            $this->updateProgress('database');

            // Création de l'instance en base
            $instance = $this->createInstance($user, $instanceData, $instanceDetails);

            $this->updateProgress('users');

            // Création des credentials
            $this->createDolibarrCredential($user, $instanceData['login'], $instanceData['password']);

            // Mise à jour des informations
            $this->newInstanceInfo = [
                'name' => $instance->name,
                'login' => $user->email,
                'password' => $instanceData['password'],
                'url' => "https://{$instance->name}.erpinnov.com",
            ];

            // Création des utilisateurs
            $newUsersInnov = new CreateUsersInnov();
            $newUsersInnov->insertIntoOtherDb(
                $instance->name,
                $user->email,
                $instanceData['api_key'],
                $instanceData['password'],
                "http://" . $instanceDetails['url']
            );

            // Notification
            Notification::send($user, new InstanceCreated($this->newInstanceInfo));

            $this->updateProgress('complete');

            $this->alert('success', 'Votre instance a été créée avec succès.');
            session()->flash('success', 'Ces informations ont été envoyées par email.');
            $this->reset(['name']);

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            \Log::error('Erreur lors de la création de l\'instance: ' . $e->getMessage());
            $this->alert('error', 'Une erreur est survenue: ' . $e->getMessage());
        } finally {
            $this->isCreating = false;
        }
    }

    private function prepareInstanceData($user)
    {
        return [
            'name' => $this->name,
            'password' => Str::random(16),
            'login' => 'admin',
            'url_suffix' => Str::slug($this->name),
            'api_key' => Str::random(32),
            'reference' => Cache::remember('next_instance_reference', 60, function () {
                return Instance::generateNextReference();
            }),
        ];
    }

    private function createInstance($user, $instanceData, $instanceDetails)
    {
        $activeSubscription = $this->getOrCreateSubscription($user);
        $entreprise = Entreprise::find($this->entreprise_id);
        $paysValue = $entreprise->pays === 'Madagascar' ? 0 : 1;

        return Instance::create([
            'user_id' => $user->id,
            'subscription_id' => $activeSubscription->id,
            'reference' => $instanceData['reference'],
            'name' => $this->name,
            'entreprise_id' => $entreprise->id,
            'url' => $instanceDetails['url'],
            'status' => Instance::STATUS_ACTIVE,
            'auth_token' => Instance::generateUniqueAuthToken(),
            'dolibarr_username' => $instanceData['login'],
            'dolibarr_password' => Hash::make($instanceData['password']),
            'dolibarr_api_key' => $instanceData['api_key'],
            'pays' => $paysValue,
        ]);
    }

    private function getOrCreateSubscription($user)
    {
        $activeSubscription = $user->activeSubscription();

        if (!$activeSubscription) {
            $freePlan = Plan::where('is_free', true)
                           ->where('is_default', true)
                           ->first();

            $activeSubscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $freePlan->id,
                'start_date' => now(),
                'end_date' => now()->addDays($freePlan->duration_days),
                'status' => 'active'
            ]);
        }

        return $activeSubscription;
    }

    private function createDolibarrCredential($user, $login, $password)
    {
        return DolibarrCredential::create([
            'user_id' => $user->id,
            'username' => $login,
            'password' => Hash::make($password),
        ]);
    }

    public function render()
    {
        $user = Auth::user();
        $instanceCount = $user->instances()->count();
        $activeSubscription = $user->activeSubscription();
        $canCreate = $user->canCreateInstance();
        $remainingInstances = $user->remainingInstances();

        return view('livewire.client.create-instances', [
            'instanceCount' => $instanceCount,
            'activeSubscription' => $activeSubscription,
            'canCreate' => $canCreate,
            'remainingInstances' => $remainingInstances,
            'showPlanSelection' => $this->showPlanSelection,
        ])->layout('layouts.main');
    }
}
