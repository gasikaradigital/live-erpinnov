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
use App\Models\DolibarrCredential;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Notifications\InstanceCreated;
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

    // Messages de validation
    protected $messages = [
        'name.required' => 'Le nom de l\'instance est obligatoire.',
        'name.unique' => 'Ce nom d\'instance est déjà utilisé.',
        'name.min' => 'Le nom de l\'instance doit contenir au moins 3 caractères.',
        'name.max' => 'Le nom de l\'instance ne peut pas dépasser 15 caractères.',
        'entreprise_id.required' => 'Le choix de l\'entreprise est obligatoire.',
        'entreprise_id.exists' => 'L\'entreprise sélectionnée n\'existe pas.',
    ];

    // Écouteurs d'événements
    protected $listeners = [
        'progressUpdate' => 'updateProgress',
        'instance-error' => 'handleError'
    ];

    // Règles de validation
    protected function rules()
    {
        return [
            'name' => ['required', 'unique:instances,name', 'min:3', 'max:15'],
            'entreprise_id' => 'required|exists:entreprises,id',
        ];
    }

    // Méthode d'initialisation
    public function mount()
    {
        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
        }
    }

    // Méthode de mise à jour de la progression
    public function updateProgress($step)
    {
        $progressSteps = [
            'validation' => 10,
            'init' => 25,
            'provision' => 40,
            'database' => 60,
            'users' => 80,
            'complete' => 100
        ];

        if (isset($progressSteps[$step])) {
            $this->progress = $progressSteps[$step];
            $this->currentStep = $step;
            $this->dispatch('progressUpdated', progress: $this->progress);
        }
    }

    // Méthode de chargement des entreprises
    public function loadEnterprises()
    {
        $this->entreprises = Cache::remember('user_entreprises_' . Auth::id(), 300, function () {
            return Auth::user()->entreprises;
        });
    }

    // Mise à jour du pays lors du changement d'entreprise
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

    // Vérification de l'éligibilité
    public function checkInstanceCreationEligibility()
    {
        $user = Auth::user();
        $this->showPlanSelection = !$user->canCreateInstance();
    }

    // Gestionnaire de changement de plan
    #[On('planChanged')]
    public function planChanged()
    {
        $this->checkInstanceCreationEligibility();
    }

    // Méthode principale de création d'instance
    public function store()
    {
        try {
            // Initialisation
            $this->isCreating = true;
            $this->progress = 0;
            $this->errorMessage = '';

            // Étape 1: Validation
            $this->updateProgress('validation');
            $this->validate();

            $user = Auth::user();
            if (!$user->canCreateInstance()) {
                throw new \Exception('Vous avez atteint votre limite de création d\'instances.');
            }

            // Étape 2: Initialisation
            $this->updateProgress('init');
            $instanceData = $this->prepareInstanceData();

            // Étape 3: Provisionnement
            $this->updateProgress('provision');
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

            // Étape 4: Base de données
            $this->updateProgress('database');
            $instance = $this->createInstance($user, $instanceData, $instanceDetails);

            // Étape 5: Configuration des utilisateurs
            $this->updateProgress('users');
            $this->setupUsers($user, $instance, $instanceData, $instanceDetails);

            // Étape 6: Finalisation
            $this->updateProgress('complete');
            $this->finalize($user, $instance, $instanceData);

        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    // Préparation des données de l'instance
    private function prepareInstanceData()
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

    // Création de l'instance en base de données
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

    // Configuration des utilisateurs
    private function setupUsers($user, $instance, $instanceData, $instanceDetails)
    {
        $this->createDolibarrCredential($user, $instanceData['login'], $instanceData['password']);

        $newUsersInnov = new CreateUsersInnov();
        $newUsersInnov->insertIntoOtherDb(
            $instance->name,
            $user->email,
            $instanceData['api_key'],
            $instanceData['password'],
            "http://" . $instanceDetails['url']
        );
    }

    // Finalisation de la création
    private function finalize($user, $instance, $instanceData)
    {
        $this->newInstanceInfo = [
            'name' => $instance->name,
            'login' => $user->email,
            'password' => $instanceData['password'],
            'url' => "http://{$instance->name}.erpinnov.com",
        ];

        Notification::send($user, new InstanceCreated($this->newInstanceInfo));

        $this->alert('success', 'Votre instance a été créée avec succès.');
        session()->flash('success', 'Ces informations ont été envoyées par email.');
        $this->reset(['name']);
        $this->isCreating = false;
    }

    // Création ou récupération de l'abonnement
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

    // Création des identifiants Dolibarr
    private function createDolibarrCredential($user, $login, $password)
    {
        return DolibarrCredential::create([
            'user_id' => $user->id,
            'username' => $login,
            'password' => Hash::make($password),
        ]);
    }

    // Gestion des erreurs
    private function handleError(\Exception $e)
    {
        $this->errorMessage = $e->getMessage();
        \Log::error('Erreur lors de la création de l\'instance: ' . $e->getMessage());
        $this->alert('error', 'Une erreur est survenue: ' . $e->getMessage());
        $this->isCreating = false;
        $this->progress = 0;
    }

    // Rendu du composant
    public function render()
    {
        $user = Auth::user();

        return view('livewire.client.create-instances', [
            'instanceCount' => $user->instances()->count(),
            'activeSubscription' => $user->activeSubscription(),
            'canCreate' => $user->canCreateInstance(),
            'remainingInstances' => $user->remainingInstances(),
            'showPlanSelection' => $this->showPlanSelection,
        ])->layout('layouts.main');
    }
}
