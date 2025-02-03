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
    public $page = 10;
    public $subscriptionEndDate;  // Ajoutez cette propriété

    public function viewDetail($instanceId)
    {
        $this->selectedInstance = Instance::with(['entreprise', 'subscription.plan'])->findOrFail($instanceId);
        $this->dispatch('show-detail-modal');
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
        $this->currentPlan = Auth::user()->activePlan();
        $this->plans = Plan::all();
        $this->loadSubscriptionInfo(); // Ajoutez cet appel
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

    // Ajoutez cette méthode
    private function loadSubscriptionInfo()
    {
        $activeSubscription = $this->user->subscriptions()->where('status', 'active')->latest()->first();
        if ($activeSubscription) {
            $this->subscriptionEndDate = $activeSubscription->end_date;
        } else {
            $this->subscriptionEndDate = null;
        }
    }

    // Ajoutez cette méthode
    public function getFormattedEndDate()
    {
        if ($this->subscriptionEndDate) {
            Carbon::setLocale('fr');
            return Carbon::parse($this->subscriptionEndDate)->format('d/m/Y');
        }
        return '-';
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.client.home-client', [
            'instances' => Instance::where('user_id', $user->id)
                ->with('subscription.plan')
                ->latest()
                ->simplePaginate($this->page),
        ])->layout('layouts.main');
    }
}
