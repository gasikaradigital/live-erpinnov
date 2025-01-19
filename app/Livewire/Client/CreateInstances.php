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

    // Propriétés de base
    public $newInstanceInfo = null;
    public $name = '';
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;

    // Propriétés pour la progression
    public bool $isCreating = false;
    public int $progress = 0;
    public string $currentStepMessage = '';

    // Définition des étapes avec leurs messages et pourcentages
    protected $steps = [
        [
            'progress' => 25,
            'message' => 'Configuration initiale en cours...'
        ],
        [
            'progress' => 50,
            'message' => 'Création de la base de données...'
        ],
        [
            'progress' => 75,
            'message' => 'Configuration de l\'instance...'
        ],
        [
            'progress' => 100,
            'message' => 'Finalisation de l\'installation...'
        ]
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

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

    public function mount()
    {
        $this->isCreating = false;
        $this->progress = 0;
        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
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
        $this->entreprises = Auth::user()->entreprises;
    }

    public function checkInstanceCreationEligibility()
    {
        $user = Auth::user();
        $this->showPlanSelection = !$user->canCreateInstance();
    }

    // Méthode pour mettre à jour la progression
    private function updateProgress(int $step)
    {
        if (isset($this->steps[$step])) {
            $this->progress = $this->steps[$step]['progress'];
            $this->currentStepMessage = $this->steps[$step]['message'];
            // Dispatch un événement pour mettre à jour la vue
            $this->dispatch('progressUpdated', [
                'progress' => $this->progress,
                'message' => $this->currentStepMessage
            ]);
        }
    }

    // Méthode pour simuler un délai (utile pour le développement)
    private function simulateDelay()
    {
        if (app()->environment('local')) {
            usleep(500000); // 0.5 seconde de délai
        }
    }

    public function store()
    {
        $this->validate();

        $user = Auth::user();
        if (!$user->canCreateInstance()) {
            $this->alert('error', 'Vous avez atteint votre limite de création d\'instances.');
            return;
        }

        try {
            $this->isCreating = true;
            $this->progress = 0;
            $this->currentStepMessage = 'Démarrage de la création...';
            $this->dispatch('startCreation');

            // Étape 1: Configuration
            $this->updateProgress(0);
            $reference = Instance::generateNextReference();
            $password_dolibarr = Str::random(16);
            $login_dolibarr = 'admin';
            $url_suffix = Str::slug($this->name);
            $api_key_dolibarr = Str::random(32);
            $this->simulateDelay();

            // Étape 2: Base de données
            $this->updateProgress(1);
            $provisioningService = new InstanceProvisioningService();
            $instanceDetails = $provisioningService->provisionInstance(
                $this->name,
                $password_dolibarr,
                $login_dolibarr,
                $url_suffix,
                $api_key_dolibarr,
                $user->email
            );

            if (!$instanceDetails) {
                throw new \Exception('Erreur lors de la création de l\'instance.');
            }
            $this->simulateDelay();

            // Étape 3: Création instance
            $this->updateProgress(2);
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

            $entreprise = Entreprise::find($this->entreprise_id);
            $instance = Instance::create([
                'user_id' => $user->id,
                'subscription_id' => $activeSubscription->id,
                'reference' => $reference,
                'name' => $this->name,
                'entreprise_id' => $entreprise->id,
                'url' => $instanceDetails['url'],
                'status' => Instance::STATUS_ACTIVE,
                'auth_token' => Instance::generateUniqueAuthToken(),
                'dolibarr_username' => $login_dolibarr,
                'dolibarr_password' => Hash::make($password_dolibarr),
                'dolibarr_api_key' => $api_key_dolibarr,
                'pays' => $entreprise->pays === 'Madagascar' ? 0 : 1,
            ]);
            $this->simulateDelay();

            // Étape 4: Finalisation
            $this->updateProgress(3);
            $this->createDolibarrCredential($user, $login_dolibarr, $password_dolibarr);

            $this->newInstanceInfo = [
                'name' => $instance->name,
                'login' => $user->email,
                'password' => $password_dolibarr,
                'url' => "https://" . $instance->name . ".erpinnov.com",
            ];

            $newUsersInnov = new CreateUsersInnov();
            $newUsersInnov->insertIntoOtherDb(
                $instance->name,
                $user->email,
                $api_key_dolibarr,
                $password_dolibarr,
                "http://" . $instanceDetails['url']
            );

            Notification::send($user, new InstanceCreated($this->newInstanceInfo));

            $this->alert('success', 'Votre instance a été créée avec succès.');
            session()->flash('success', 'Ces informations ont été envoyées par email.');
            $this->reset(['name']);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'instance: ' . $e->getMessage());
            $this->alert('error', 'Une erreur est survenue lors de la création de l\'instance.');
        } finally {
            $this->dispatch('creationCompleted');
            $this->isCreating = false;
        }
    }

    private function createDolibarrCredential($user, $login, $password)
    {
        DolibarrCredential::create([
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
