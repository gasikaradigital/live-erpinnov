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
use Illuminate\Support\Facades\Notification;
use App\Services\InstanceProvisioningService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateInstances extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    // Propriétés existantes
    public $newInstanceInfo = null;
    public $name = '';
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;

    // Nouvelles propriétés pour la progression
    public $isCreating = false;
    public $progress = 0;
    public $currentStep = '';
    public $steps = [
        'validation' => 'Validation des données',
        'provisioning' => 'Préparation de l\'environnement',
        'database' => 'Configuration de la base de données',
        'finalizing' => 'Finalisation de l\'installation'
    ];

    // Règles de validation
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

    // Méthodes du cycle de vie
    public function mount()
    {
        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
        }
    }

    public function loadEnterprises()
    {
        $this->entreprises = Auth::user()->entreprises;
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

    // Méthode principale de création d'instance
    public function store()
    {
        try {
            // Initialisation de la création
            $this->startCreation();

            // Étape 1: Validation
            $this->handleValidation();

            // Étape 2: Préparation
            $instanceConfig = $this->prepareInstanceConfiguration();

            // Étape 3: Création de l'instance
            $instance = $this->createNewInstance($instanceConfig);

            // Étape 4: Finalisation
            $this->finalizeInstanceCreation($instance, $instanceConfig);

            // Succès
            $this->handleSuccess();

        } catch (\Exception $e) {
            $this->handleError($e);
        } finally {
            $this->finishCreation();
        }
    }

    // Méthodes de support pour la création d'instance
    private function startCreation()
    {
        $this->isCreating = true;
        $this->progress = 0;
        $this->currentStep = 'validation';
    }

    private function handleValidation()
    {
        $this->validate();
        $user = Auth::user();

        if (!$user->canCreateInstance()) {
            throw new \Exception('Vous avez atteint votre limite de création d\'instances.');
        }

        $this->updateProgress('validation', 25);
    }

    private function prepareInstanceConfiguration()
    {
        $this->currentStep = 'provisioning';

        $config = [
            'reference' => Instance::generateNextReference(),
            'password' => Str::random(16),
            'login' => 'admin',
            'url_suffix' => Str::slug($this->name),
            'api_key' => Str::random(32)
        ];

        $provisioningService = new InstanceProvisioningService();
        $instanceDetails = $provisioningService->provisionInstance(
            $this->name,
            $config['password'],
            $config['login'],
            $config['url_suffix'],
            $config['api_key'],
            Auth::user()->email
        );

        if (!$instanceDetails) {
            throw new \Exception('Erreur lors de la création de l\'instance.');
        }

        $config['instanceDetails'] = $instanceDetails;
        $this->updateProgress('provisioning', 50);

        return $config;
    }

    private function createNewInstance($config)
    {
        $this->currentStep = 'database';

        $user = Auth::user();
        $activeSubscription = $this->setupSubscription($user);
        $entreprise = Entreprise::find($this->entreprise_id);

        $instance = Instance::create([
            'user_id' => $user->id,
            'subscription_id' => $activeSubscription->id,
            'reference' => $config['reference'],
            'name' => $this->name,
            'entreprise_id' => $entreprise->id,
            'url' => $config['instanceDetails']['url'],
            'status' => Instance::STATUS_ACTIVE,
            'auth_token' => Instance::generateUniqueAuthToken(),
            'dolibarr_username' => $config['login'],
            'dolibarr_password' => Hash::make($config['password']),
            'dolibarr_api_key' => $config['api_key'],
            'pays' => $entreprise->pays === 'Madagascar' ? 0 : 1,
        ]);

        $this->updateProgress('database', 75);

        return $instance;
    }

    private function finalizeInstanceCreation($instance, $config)
    {
        $this->currentStep = 'finalizing';

        // Création des credentials Dolibarr
        $this->createDolibarrCredential(
            Auth::user(),
            $config['login'],
            $config['password']
        );

        // Préparation des informations de l'instance
        $this->newInstanceInfo = [
            'name' => $instance->name,
            'login' => Auth::user()->email,
            'password' => $config['password'],
            'url' => "https://{$instance->name}.erpinnov.com",
        ];

        // Création des utilisateurs Innov
        $newUsersInnov = new CreateUsersInnov();
        $newUsersInnov->insertIntoOtherDb(
            $instance->name,
            Auth::user()->email,
            $config['api_key'],
            $config['password'],
            "http://" . $config['instanceDetails']['url']
        );

        // Envoi de la notification
        Notification::send(Auth::user(), new InstanceCreated($this->newInstanceInfo));

        $this->updateProgress('finalizing', 100);
    }

    private function handleSuccess()
    {
        $this->alert('success', 'Votre instance a été créée avec succès.');
        session()->flash('success', 'Ces informations ont été envoyées par email.');
        $this->reset(['name']);
    }

    private function handleError(\Exception $e)
    {
        \Log::error('Erreur lors de la création de l\'instance: ' . $e->getMessage());
        $this->alert('error', 'Une erreur inattendue est survenue. ' . $e->getMessage());
    }

    private function finishCreation()
    {
        $this->isCreating = false;
        $this->progress = 0;
    }

    // Méthodes utilitaires
    private function updateProgress($step, $value)
    {
        $this->currentStep = $step;
        $this->progress = $value;
        $this->dispatch('progressUpdated', [
            'progress' => $this->progress,
            'step' => $this->steps[$step]
        ]);
    }

    private function setupSubscription($user)
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
        DolibarrCredential::create([
            'user_id' => $user->id,
            'username' => $login,
            'password' => Hash::make($password),
        ]);
    }

    // Méthode de rendu
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
