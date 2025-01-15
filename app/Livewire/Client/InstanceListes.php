<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Instance;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InstanceListes extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';
    public $page = 4;
    public $isLoading = true;
    public $showPlanSelection = false;

    public $instanceId;
    public $name, $reference, $created_at, $expiration_date, $status, $planType, $dolibarr_username, $url, $modules;

    public function openPlanSelection()
    {
        $this->showPlanSelection = true;
    }

    public function viewDetail($instanceId)
    {
        $this->isLoading = true;

        $this->instanceId = $instanceId;

        $detail = Instance::with('subscription.plan')->findOrFail($instanceId);

        $this->name = $detail->name;
        $this->reference = $detail->reference;
        $this->created_at = $detail->created_at->format('d/m/Y H:i');
        $this->expiration_date = $detail->subscription->end_date->format('d/m/Y H:i');
        $this->status = ucfirst($detail->status);
        $this->planType = $detail->subscription->plan->is_free ? 'Gratuit' : 'Pro';
        $this->dolibarr_username = $detail->dolibarr_username;
        $this->url = "https://{$detail->url}";
        $this->modules = $detail->subscription->plan->modules ?? []; // Récupère les modules du plan

        $this->isLoading = false;
    }


    public function delete($instanceId)
    {
        $instance = Instance::findOrFail($instanceId);
        $this->authorize('delete', $instance);
        $instance->delete();
        $this->alert('success', 'Instance supprimée avec succès.');
    }

    public function upgradeToPro($instanceId)
    {
        $instance = Instance::findOrFail($instanceId);
        $this->authorize('update', $instance);

        $proPlan = Plan::where('is_free', false)->first();

        if (!$proPlan) {
            $this->alert('error', 'Plan Pro non trouvé. Veuillez contacter l\'administrateur.');
            return;
        }

        // Ici, vous devriez implémenter la logique de paiement
        // Pour cet exemple, nous supposons que le paiement a réussi

        $instance->subscription->update([
            'plan_id' => $proPlan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($proPlan->duration_days),
        ]);

        $this->alert('success', 'Votre instance a été mise à niveau vers le plan pro avec succès.');
    }

    public function render()
    {
        $user = Auth::user();
        return view('livewire.client.instance-listes', [
            'showPlanSelection' => $this->showPlanSelection,
            'instances' => Instance::where('user_id', $user->id)
                ->with('subscription.plan')
                ->latest()
                ->simplePaginate($this->page),
        ])->layout('layouts.main');
    }
}
