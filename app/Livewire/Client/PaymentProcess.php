<?php

namespace App\Livewire\Client;

use App\Models\Plan;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Subscription;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentProcess extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    public $uuid;
    public $plan;
    public $cardholderName;
    public $cardNumber;
    public $cardExpiry;
    public $cardCVC;
    public $isAnnual = false;
    public $total;

    protected $rules = [
        'cardholderName' => 'required|string|max:255',
        'cardNumber' => 'required|numeric|digits:16',
        'cardExpiry' => 'required|date_format:m/y',
        'cardCVC' => 'required|numeric|digits:3',
    ];

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->plan = Plan::where('uuid', $uuid)->firstOrFail();
        $this->calculateTotal();
    }

    public function updatedIsAnnual()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->plan) {
            $this->total = $this->isAnnual ? $this->plan->discounted_yearly_price : $this->plan->price_monthly;
        } else {
            $this->total = 0;
        }
    }

    public function getMonthlyPriceProperty()
    {
        return $this->plan ? $this->plan->price_monthly : 0;
    }

    public function getYearlyPriceProperty()
    {
        return $this->plan ? $this->plan->discounted_yearly_price : 0;
    }

    public function getYearlySavingsProperty()
    {
        if (!$this->plan) return 0;
        $regularYearlyPrice = $this->plan->price_monthly * 12;
        return $regularYearlyPrice - $this->yearlyPrice;
    }


    public function changePlan($uuid)
    {
        $this->uuid = $uuid;
        $this->plan = Plan::where('uuid', $uuid)->firstOrFail();
        $this->calculateTotal();
    }

    public function processPayment()
    {
        $this->validate();

        $user = Auth::user();

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $this->plan->id,
            'start_date' => now(),
            'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
            'status' => 'active'
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'plan_id' => $this->plan->id,
            'subscription_id' => $subscription->id,
            'amount' => $this->total,
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'transaction_id' => 'sim_' . uniqid(),
            'cardholder_name' => $this->cardholderName,
        ]);

        $user->subscriptions()->where('id', '!=', $subscription->id)->update(['status' => 'expired']);

        $this->alert('success', 'Paiement effectué avec succès. Votre plan a été mis à jour pour ' . ($this->isAnnual ? '1 an' : '1 mois') . '.');

        return redirect()->route('espaceClient');
    }

    public function render()
    {
        return view('livewire.client.payment-process', [
            'monthlyPrice' => $this->monthlyPrice,
            'yearlyPrice' => $this->yearlyPrice,
            'yearlySavings' => $this->yearlySavings,
        ])->layout('layouts.homeClient');
    }
}
