<?php

namespace App\Livewire\Client;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use Livewire\Component;
use App\Models\Instance;
use App\Models\Entreprise;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Subscription;
use Livewire\WithPagination;
use App\Jobs\CreateDolibarrInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Services\FastInstanceProvisioningService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateInstances extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $newInstanceInfo = null;
    public $name = ''; // Nom personnalisé pour la création manuelle
    public $autoName = ''; // Nom généré automatiquement
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;
    public $currentDateTime;
    public $currentUser;
    public $isVerifying = false;
    public $creationType = 'auto'; // 'auto' ou 'manual' pour le type de création

    protected function rules()
    {
        return [
            'name' => [
                'required_if:creationType,manual',
                'unique:instances,name',
                'min:3',
                'max:15',
                'regex:/^[a-zA-Z0-9_-]*$/'
            ],
            'autoName' => [
                'required_if:creationType,auto',
                'unique:instances,name',
                'min:3',
                'max:15',
                'regex:/^[a-zA-Z0-9_-]*$/'
            ],
            'entreprise_id' => 'required|exists:entreprises,id',
        ];
    }

    protected $messages = [
        'name.required_if' => 'Le nom de l\'instance est requis pour la création manuelle.',
        'name.unique' => 'Ce nom d\'instance est déjà utilisé.',
        'name.min' => 'Le nom doit contenir au moins 3 caractères.',
        'name.max' => 'Le nom ne peut pas dépasser 15 caractères.',
        'name.regex' => 'Le nom ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
        'autoName.required_if' => 'Un nom d\'instance automatique est requis.',
        'autoName.unique' => 'Ce nom d\'instance généré est déjà utilisé.',
        'autoName.min' => 'Le nom doit contenir au moins 3 caractères.',
        'autoName.max' => 'Le nom ne peut pas dépasser 15 caractères.',
        'autoName.regex' => 'Le nom ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
        'entreprise_id.required' => 'Veuillez sélectionner une entreprise.',
        'entreprise_id.exists' => 'L\'entreprise sélectionnée n\'existe pas.',
    ];

    public function updatedCreationType()
    {
        $this->reset(['name', 'autoName']); // Réinitialiser les champs quand le type change
        if ($this->creationType === 'auto' && $this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id); // Générer un nom automatique si une entreprise est sélectionnée
        }
    }

    public function updatedEntrepriseId($value)
    {
        if ($value) {
            $entreprise = Entreprise::find($value);
            if ($entreprise) {
                $this->selectedPays = $entreprise->pays;
                // Générer un nom automatique basé sur le nom de l’entreprise uniquement pour création auto
                if ($this->creationType === 'auto') {
                    $this->autoName = $this->generateInstanceName($entreprise->name);
                }
            }
        } else {
            $this->selectedPays = null;
            $this->autoName = '';
        }
    }

    public function mount()
    {
        $user = Auth::user();

        // Vérifier si le profil est complet
        if (!$user->profile->isComplete()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Veuillez compléter votre profil.');
        }

        // Vérifier si un plan est sélectionné ou si le paiement/trial est complété
        if (!session()->has('selected_plan') && !session()->has('payment_completed') && !session()->has('trial_activated')) {
            return redirect()->route('plans.selection')
                ->with('warning', 'Veuillez sélectionner un plan.');
        }

        $selectedPlan = session()->get('selected_plan', []);

        // Vérifier le paiement pour les plans payants ou l’activation du trial
        if (!isset($selectedPlan['is_free']) && !session()->has('payment_completed') && !session()->has('trial_activated')) {
            return redirect()->route('payment.process', ['uuid' => $selectedPlan['uuid'] ?? ''])
                ->with('warning', 'Veuillez compléter le processus de paiement.');
        }

        // Vérifier si l'entreprise existe
        if (!$user->entreprises()->exists()) {
            return redirect()->route('entreprise.create')
                ->with('warning', 'Veuillez créer une entreprise avant de créer une instance.');
        }

        // Initialiser les données
        $this->currentDateTime = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $this->currentUser = $user->email;

        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
        }

        // Vérifier si c’est une mise à niveau ou une création après trial/paiement
        if (request()->has('upgrade') && request()->has('instance')) {
            $this->instanceId = request()->input('instance');
            $this->isUpgrade = true;
            $this->currentInstance = Instance::findOrFail($this->instanceId);
        }

        if ($this->newInstanceInfo) {
            $instance = Instance::where('name', $this->newInstanceInfo['name'])->first();
            $this->isVerifying = $instance && $instance->status === 'pending';
        }
    }

    public function loadEnterprises()
    {
        $this->entreprises = Auth::user()->entreprises;
    }

    public function checkInstanceCreationEligibility()
    {
        $user = Auth::user();
        $activeSubscription = $user->subscriptions()
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();

        if (!$activeSubscription) {
            $this->showPlanSelection = true;
            return;
        }

        // Si c'est un abonnement payant, vérifier la limite du plan
        if ($activeSubscription->status === 'active') {
            $this->showPlanSelection = $user->instances()
                ->whereHas('subscription', function($query) use ($activeSubscription) {
                    $query->where('id', $activeSubscription->id);
                })->count() >= ($activeSubscription->plan->instance_limit ?? 1);
            return;
        }

        // Si c'est un essai, vérifier la limite d'une instance
        if ($activeSubscription->status === 'trial') {
            $this->showPlanSelection = $user->instances()
                ->whereHas('subscription', function($query) use ($activeSubscription) {
                    $query->where('id', $activeSubscription->id);
                })->count() >= 1;
        }
    }

    private function generateInstanceName($entrepriseName)
    {
        $prefix = strtolower(Str::slug($entrepriseName));
        $randomString = Str::random(4);
        $name = $prefix . '-' . $randomString;

        // Vérifier l'unicité et générer un nouveau nom si nécessaire
        while (Instance::where('name', $name)->exists()) {
            $randomString = Str::random(4);
            $name = $prefix . '-' . $randomString;
        }

        return $name;
    }

    private function createInstanceRecord($user, $instanceData)
    {
        $reference = Instance::generateNextReference();

        // Récupérer le plan sélectionné depuis la session
        $selectedPlan = session()->get('selected_plan', []);

        // Obtenir ou créer l'abonnement en fonction du type de plan
        $activeSubscription = $selectedPlan['is_free'] ?? false
            ? $this->createFreeSubscription($user)
            : $user->activeSubscription();

        $entreprise = Entreprise::find($this->entreprise_id);

        $instanceName = $this->creationType === 'auto' ? $this->autoName : $this->name;

        $instance = Instance::create([
            'user_id' => $user->id,
            'subscription_id' => $activeSubscription->id,
            'reference' => $reference,
            'name' => $instanceName,
            'entreprise_id' => $entreprise->id,
            'status' => ($selectedPlan['is_free'] ?? false) ? 'pending' : 'active',
            'url' => $instanceName . '.erpinnov.com',
            'auth_token' => Instance::generateUniqueAuthToken(),
            'dolibarr_username' => $instanceData['login_dolibarr'],
            'dolibarr_password' => Hash::make($instanceData['password_dolibarr']),
            'dolibarr_api_key' => $instanceData['api_key_dolibarr'],
            'pays' => $entreprise->pays === 'Madagascar' ? 0 : 1,
            'created_at' => $this->currentDateTime,
            'created_by' => $this->currentUser,
        ]);

        // Si c'est un plan gratuit, nettoyer la session après la création
        if ($selectedPlan['is_free'] ?? false) {
            session()->forget(['selected_plan']);
        }

        return $instance;
    }

    private function createFreeSubscription($user)
    {
        $freePlan = Plan::where('is_free', true)->where('is_default', true)->first();

        // Vérifier si l'utilisateur a déjà un abonnement gratuit actif
        $existingFreeSubscription = $user->subscriptions()
            ->where('plan_id', $freePlan->id)
            ->where('status', 'active')
            ->first();

        if ($existingFreeSubscription) {
            return $existingFreeSubscription;
        }

        // Créer un nouvel abonnement gratuit
        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
            'start_date' => $this->currentDateTime,
            'end_date' => Carbon::parse($this->currentDateTime)->addDays($freePlan->duration_days ?? 14), // 14 jours par défaut pour trial
            'status' => 'active',
            'created_by' => $this->currentUser
        ]);
    }

    public function store()
    {
        $this->dispatch('instanceCreationStarted');
        $this->validate();

        $user = Auth::user();

        try {
            $instanceData = [
                'name' => $this->creationType === 'auto' ? $this->autoName : $this->name,
                'password_dolibarr' => Str::random(16),
                'login_dolibarr' => 'admin',
                'url_suffix' => Str::slug($this->creationType === 'auto' ? $this->autoName : $this->name),
                'api_key_dolibarr' => Str::random(32),
            ];

            $selectedPlan = session()->get('selected_plan', []);
            $isTrial = session()->has('trial_activated') && !($selectedPlan['is_free'] ?? false);
            $isPaid = session()->has('payment_completed');

            // Récupérer ou créer la souscription appropriée
            $activeSubscription = null;
            if ($isTrial) {
                $activeSubscription = $user->subscriptions()
                    ->where('status', Subscription::STATUS_TRIAL)
                    ->where('plan_id', $selectedPlan['plan_id'] ?? null)
                    ->first();
            } elseif ($isPaid) {
                $activeSubscription = $this->createPaidSubscription($user, $selectedPlan);
            } else {
                $activeSubscription = $this->createFreeSubscription($user);
            }

            $entreprise = Entreprise::find($this->entreprise_id);

            $instance = Instance::create([
                'user_id' => $user->id,
                'subscription_id' => $activeSubscription->id,
                'reference' => Instance::generateNextReference(),
                'name' => $instanceData['name'],
                'entreprise_id' => $entreprise->id,
                'status' => $isPaid ? 'active' : ($isTrial ? 'pending' : 'active'),
                'url' => $instanceData['name'] . '.erpinnov.com',
                'auth_token' => Instance::generateUniqueAuthToken(),
                'dolibarr_username' => $instanceData['login_dolibarr'],
                'dolibarr_password' => Hash::make($instanceData['password_dolibarr']),
                'dolibarr_api_key' => $instanceData['api_key_dolibarr'],
                'pays' => $entreprise->pays === 'Madagascar' ? 0 : 1,
                'created_at' => $this->currentDateTime,
                'created_by' => $this->currentUser,
            ]);

            // Utilisation du service de provisionnement
            $fastProvisioning = new FastInstanceProvisioningService();
            $success = $fastProvisioning->createInstance($instanceData, $user, $instance);

            if ($success) {
                $this->newInstanceInfo = [
                    'name' => $instance->name,
                    'login' => $user->email,
                    'password' => $instanceData['password_dolibarr'],
                    'url' => "http://" . $instance->name . ".erpinnov.com",
                    'created_at' => $this->currentDateTime,
                    'created_by' => $this->currentUser
                ];

                // Nettoyer les données de session après création réussie
                session()->forget(['selected_plan', 'payment_completed', 'trial_activated']);

                $this->alert('success', 'Instance créée avec succès. Vous allez être redirigé vers votre espace client.');
                $this->reset(['name', 'autoName', 'creationType']);

                // Rediriger vers l'espace client après un court délai
                return redirect()->route('espaceClient')
                    ->with('success', 'Instance créée avec succès.');
            }

        } catch (\Exception $e) {
            logger()->error('Erreur création instance:', [
                'error' => $e->getMessage(),
                'user' => $user->id,
                'selectedPlan' => $selectedPlan
            ]);
            $this->alert('error', 'Erreur lors de la création de l\'instance.');
        }

        $this->dispatch('instanceCreationEnded');
    }

    private function createPaidSubscription($user, $selectedPlan)
    {
        $plan = Plan::find($selectedPlan['plan_id'] ?? null);
        $durationDays = $plan ? $plan->duration_days : 30; // Durée par défaut de 30 jours si non spécifié

        // Vérifier si un sous-plan est spécifié dans la session
        $subPlan = null;
        if (isset($selectedPlan['sub_plan_id'])) {
            $subPlan = SubPlan::find($selectedPlan['sub_plan_id']);
            if ($subPlan) {
                // Si un sous-plan existe, utiliser sa durée ou une durée par défaut
                $durationDays = $plan->duration_days ?? 30; // Maintenir la durée du plan principal
            }
        }

        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $selectedPlan['plan_id'] ?? null,
            'sub_plan_id' => $selectedPlan['sub_plan_id'] ?? null,
            'start_date' => now(),
            'end_date' => now()->addDays($durationDays), // Utiliser la durée du plan
            'status' => Subscription::STATUS_ACTIVE,
            'created_by' => $this->currentUser
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
            'currentDateTime' => $this->currentDateTime,
            'currentUser' => $this->currentUser,
            'entreprises' => $this->entreprises,
        ])->layout('layouts.main');
    }

    public function getInstanceStatus($instanceName)
    {
        $instance = Instance::where('name', $instanceName)->first();
        return $instance ? $instance->status : null;
    }
}
