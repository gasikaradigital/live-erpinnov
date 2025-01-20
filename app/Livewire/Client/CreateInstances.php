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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\CreateDolibarrInstance;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateInstances extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'echo-private:instance.*,InstanceCreated' => 'handleInstanceCreated'
    ];

    public $newInstanceInfo = null;
    public $name = '';
    public $entreprises;
    public $entreprise_id;
    public $showPlanSelection = false;
    public $selectedPays = null;
    public $isVerifying = false;
    public $instanceVerificationId = null;

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

    public function checkInstanceCreationEligibility()
    {
        $user = Auth::user();
        $this->showPlanSelection = !$user->canCreateInstance();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'unique:instances,name', 'min:3', 'max:15'],
            'entreprise_id' => 'required|exists:entreprises,id',
        ];
    }

    public function store()
    {
        \Log::info('Début de la création de l\'instance');

        $this->dispatch('instanceCreationStarted');
        $this->validate();

        try {
            \Log::info('Validation passée, préparation des données');

            $instanceData = [
                'name' => $this->name,
                'password_dolibarr' => Str::random(16),
                'login_dolibarr' => 'admin',
                'url_suffix' => Str::slug($this->name),
                'api_key_dolibarr' => Str::random(32),
            ];

            \Log::info('Création de l\'enregistrement instance');
            $instance = $this->createInstanceRecord(Auth::user(), $instanceData);

            \Log::info('Instance créée avec ID: ' . $instance->id);

            $this->isVerifying = true;
            $this->instanceVerificationId = $instance->id;

            \Log::info('Dispatch du job');
            CreateDolibarrInstance::dispatch($instanceData, Auth::user(), $instance);

            $this->reset(['name']);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'instance: ' . $e->getMessage());
            $this->alert('error', 'Une erreur est survenue lors de la création de l\'instance.');
        }

        \Log::info('Fin de la méthode store');
        $this->dispatch('instanceCreationEnded');
    }

    public function handleInstanceCreated($event)
    {
        $instance = Instance::find($event['instance']['id']);
        if ($instance && $instance->id === $this->instanceVerificationId) {
            $this->checkInstanceStatus();
        }
    }

    public function checkInstanceStatus()
    {
        \Log::info('Vérification du statut de l\'instance: ' . $this->instanceVerificationId);

        if (!$this->instanceVerificationId) {
            \Log::info('Pas d\'ID d\'instance à vérifier');
            return;
        }

        $instance = Instance::find($this->instanceVerificationId);

        if (!$instance) {
            \Log::error('Instance non trouvée: ' . $this->instanceVerificationId);
            return;
        }

        \Log::info('Statut de l\'instance: ' . $instance->status);

        if ($instance->status === 'active') {
            $this->isVerifying = false;
            $this->instanceVerificationId = null;
            $this->newInstanceInfo = [
                'name' => $instance->name,
                'login' => Auth::user()->email,
                'password' => $instance->dolibarr_password,
                'url' => $instance->url
            ];
            $this->alert('success', 'Votre instance est maintenant prête !');
        } elseif ($instance->status === 'failed') {
            $this->isVerifying = false;
            $this->instanceVerificationId = null;
            $this->alert('error', 'La création de l\'instance a échoué.');
        }
    }

    private function createInstanceRecord($user, $instanceData)
    {
        $reference = Instance::generateNextReference();
        $activeSubscription = $user->activeSubscription() ?? $this->createFreeSubscription($user);
        $entreprise = Entreprise::find($this->entreprise_id);

        return Instance::create([
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
        ]);
    }

    private function createFreeSubscription($user)
    {
        $freePlan = Plan::where('is_free', true)->where('is_default', true)->first();
        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($freePlan->duration_days),
            'status' => 'active'
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
