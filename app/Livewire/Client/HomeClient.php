<?php

namespace App\Livewire\Client;

use Carbon\Carbon;
use App\Models\Plan;
use Livewire\Component;
use App\Models\Instance;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class HomeClient extends Component
{
    use WithPagination, LivewireAlert;

    public $currentPlan;
    public $plans;
    public $user;
    public $statistics;
    public $selectedInstance = null;
    public $perPage = 10;
    public $subscriptionEndDate;
    public $isLoading = true;
    public $showPlanSelection = false;

    public $instanceId;
    public $name, $reference, $created_at, $expiration_date, $status, $planType, $dolibarr_username, $url, $modules;

    protected function prepareInstance($instance)
    {
        $subscription = $instance->subscription;
        $plan = $subscription?->plan;
        $subPlan = $subscription?->subPlan;
        $isTrialPeriod = $subscription?->status === 'trial';
        $remainingDays = $isTrialPeriod ? now()->diffInDays($subscription?->end_date, false) : 0;

        $instance->plan = $plan;
        $instance->subPlan = $subPlan;
        $instance->isTrialPeriod = $isTrialPeriod;
        $instance->remainingDays = $remainingDays;

        return $instance;
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

    public function closeModal()
    {
        $this->selectedInstance = null;
        $this->dispatch('close-detail-modal');
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadStatistics();
        $this->currentPlan = $this->user->activePlan();
        $this->plans = Plan::all();
        $this->loadSubscriptionInfo();
    }

    private function loadStatistics()
    {
        $this->statistics = [
            'totalInstances' => $this->user->instances()->count(),
            'activeInstances' => $this->user->instances()->active()->count(),
            'expiredInstances' => $this->user->instances()->expired()->count(),
            'paidInstances' => $this->user->instances()->paid()->count(),
        ];
    }

    private function loadSubscriptionInfo()
    {
        $activeSubscription = $this->user->subscriptions()
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('status', 'trial');
            })
            ->latest()
            ->first();

        $this->subscriptionEndDate = $activeSubscription?->end_date;
    }

    public function getFormattedEndDate()
    {
        if ($this->subscriptionEndDate) {
            Carbon::setLocale('fr');
            return Carbon::parse($this->subscriptionEndDate)->format('d/m/Y');
        }
        return '-';
    }

    public function delete($instanceId)
    {
        $instance = Instance::findOrFail($instanceId);
        $this->authorize('delete', $instance);
        $instance->delete();
        $this->alert('success', 'Instance supprimée avec succès.');
    }


    public function render()
    {
        $instances = Instance::where('user_id', $this->user->id)
            ->with('subscription.plan')
            ->latest()
            ->simplePaginate($this->perPage);

        $instances->through(fn ($instance) => $this->prepareInstance($instance));

        return view('livewire.client.home-client', [
            'instances' => $instances,
        ])->layout('layouts.main');
    }
}
