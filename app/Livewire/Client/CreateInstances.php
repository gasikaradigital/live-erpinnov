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
    public $name = '';
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;
    public $currentDateTime;
    public $currentUser;
    public $isVerifying = false;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'unique:instances,name',
                'min:3',
                'max:15',
                'regex:/^[a-zA-Z0-9_-]*$/'
            ],
            'entreprise_id' => 'required|exists:entreprises,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom de l\'instance est requis.',
        'name.unique' => 'Ce nom d\'instance est déjà utilisé.',
        'name.min' => 'Le nom doit contenir au moins 3 caractères.',
        'name.max' => 'Le nom ne peut pas dépasser 15 caractères.',
        'name.regex' => 'Le nom ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
        'entreprise_id.required' => 'Veuillez sélectionner une entreprise.',
        'entreprise_id.exists' => 'L\'entreprise sélectionnée n\'existe pas.',
    ];

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

    public function mount()
    {
        // Initialiser la date et l'utilisateur actuels
        $this->currentDateTime = Carbon::now('UTC')->format('Y-m-d H:i:s');
        $this->currentUser = Auth::user()->email;

        // Vérifier si un plan a été sélectionné
        if (!session()->has('selected_plan')) {
            return redirect()->route('plans.selection');
        }

        $selectedPlan = session()->get('selected_plan');

        // Si c'est un plan payant, vérifier le paiement
        if (!$selectedPlan['is_free'] && !session()->has('payment_completed')) {
            return redirect()->route('payment.process', ['uuid' => $selectedPlan['uuid']]);
        }

        $this->checkInstanceCreationEligibility();
        $this->loadEnterprises();

        if ($this->entreprise_id) {
            $this->updatedEntrepriseId($this->entreprise_id);
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
        $this->showPlanSelection = !$user->canCreateInstance();
    }

    private function generateInstanceName()
    {
        $prefix = strtolower(Str::slug(Auth::user()->name));
        $randomString = Str::random(4);
        return $prefix . '-' . $randomString;
    }

    private function createInstanceRecord($user, $instanceData)
    {
        $reference = Instance::generateNextReference();

        // Récupérer le plan sélectionné depuis la session
        $selectedPlan = session()->get('selected_plan');

        // Obtenir ou créer l'abonnement en fonction du type de plan
        $activeSubscription = $selectedPlan['is_free']
            ? $this->createFreeSubscription($user)
            : $user->activeSubscription();

        $entreprise = Entreprise::find($this->entreprise_id);

        $instance = Instance::create([
            'user_id' => $user->id,
            'subscription_id' => $activeSubscription->id,
            'reference' => $reference,
            'name' => $instanceData['name'],
            'entreprise_id' => $entreprise->id,
            'status' => 'pending',
            'url' => $instanceData['name'] . '.erpinnov.com',
            'auth_token' => Instance::generateUniqueAuthToken(),
            'dolibarr_username' => $instanceData['login_dolibarr'],
            'dolibarr_password' => Hash::make($instanceData['password_dolibarr']),
            'dolibarr_api_key' => $instanceData['api_key_dolibarr'],
            'pays' => $entreprise->pays === 'Madagascar' ? 0 : 1,
            'created_at' => $this->currentDateTime,
            'created_by' => $this->currentUser,
        ]);

        // Si c'est un plan gratuit, nettoyer la session après la création
        if ($selectedPlan['is_free']) {
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
            'end_date' => Carbon::parse($this->currentDateTime)->addDays($freePlan->duration_days ?? 30),
            'status' => 'active',
            'created_by' => $this->currentUser
        ]);
    }

    public function store()
    {
        $this->dispatch('instanceCreationStarted');
        $this->validate();
        $user = Auth::user();
          /** @var User $user */

        $activeSubscription = $user->activeSubscription();

        if ($activeSubscription && $activeSubscription->status === Subscription::STATUS_TRIAL) {
            if ($user->hasReachedTrialLimit()) {
                $this->alert('error', 'Limite atteinte pour la période d\'essai. Veuillez mettre à niveau votre plan.');
                return redirect()->route('payment.process', ['uuid' => $activeSubscription->plan->uuid]);
            }
        }

        try {
            $instanceData = [
                'name' => $this->name ?: $this->generateInstanceName(),
                'password_dolibarr' => Str::random(16),
                'login_dolibarr' => 'admin',
                'url_suffix' => Str::slug($this->name),
                'api_key_dolibarr' => Str::random(32),
            ];

            $instance = $this->createInstanceRecord($user, $instanceData);

            // Utilisation du nouveau service
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
                $this->alert('success', 'Instance créée avec succès.');
                $this->reset(['name']);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur création: ' . $e->getMessage());
            $this->alert('error', 'Erreur lors de la création.');
        }

        $this->dispatch('instanceCreationEnded');
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
        ])->layout('layouts.main');
    }

    public function getInstanceStatus($instanceName)
    {
        $instance = Instance::where('name', $instanceName)->first();
        return $instance ? $instance->status : null;
    }
}
