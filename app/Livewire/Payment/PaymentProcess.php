<?php
namespace App\Livewire\Payment;

use App\Models\Plan;
use App\Models\Payment;
use App\Models\SubPlan;
use Livewire\Component;
use App\Models\Instance;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentProcess extends Component
{
   use LivewireAlert;

   public $uuid;
   public $plan;
   public $selectedSubPlan;
   public $paymentMethod = 'VISA'; //payement default
   public $cardInfo = [
       'name' => '',
       'number' => '',
       'expiry' => '',
       'cvc' => ''
   ];
   public $mobileNumber;
   public $isAnnual = false;
   public $hasUsedTrial;
   public $currentInstance;

   protected function rules()
   {
       $rules = [];

       if ($this->paymentMethod === 'VISA') {
           $rules = [
               'cardInfo.name' => 'required',
               'cardInfo.number' => 'required|numeric|digits:16',
               'cardInfo.expiry' => 'required|date_format:m/y',
               'cardInfo.cvc' => 'required|numeric|digits:3'
           ];
       } elseif (in_array($this->paymentMethod, ['OrangeMoney', 'Mvola'])) {
           $rules = [
               'mobileNumber' => 'required|regex:/^03[2-4][0-9]{7}$/'
           ];
       }

       return $rules;
   }

   public function mount($uuid)
   {
    $this->hasUsedTrial = Auth::user()
        ->subscriptions()
        ->where('status', Subscription::STATUS_TRIAL)
        ->exists();

        $this->currentInstance = Instance::where('user_id', Auth::id())
        ->whereHas('subscription', function($q) {
            $q->where('status', Subscription::STATUS_TRIAL);
        })->first();

        $selectedPlan = session('selected_plan');
            if (!$selectedPlan || $selectedPlan['uuid'] !== $uuid) {
                return redirect()->route('plans.selection');
            }

       $this->uuid = $uuid;
       $subPlanId = request()->query('sub_plan');

       // Charger le plan avec ses sous-plans
       $this->plan = Plan::with('subPlans')
                        ->where('uuid', $uuid)
                        ->firstOrFail();

       // Récupérer le sous-plan spécifique
       if ($subPlanId) {
           $this->selectedSubPlan = $this->plan->subPlans
               ->where('id', $subPlanId)
               ->first();

           if (!$this->selectedSubPlan) {
               return redirect()->route('plans.selection');
           }
       }

   }

   protected function loadPlan()
   {
       $this->plan = Plan::where('uuid', $uuid)->firstOrFail();
   }

   public function startTrial()
   {
        if ($this->hasUsedTrial) {
            $this->alert('error', 'Vous avez déjà utilisé votre période d\'essai.');
            return;
    }
      $subscription = Subscription::create([
          'user_id' => Auth::id(),
          'plan_id' => $this->plan->id,
          'start_date' => now(),
          'end_date' => now()->addDays(15),
          'status' => Subscription::STATUS_TRIAL
      ]);

      session([
          'selected_plan' => [
              'uuid' => $this->plan->uuid,
              'name' => $this->plan->name,
              'is_free' => false,
              'subscription_id' => $subscription->id
          ],
          'payment_completed' => true
      ]);

      return redirect()->route('instance.create');
   }

    public function processPayment()
    {
        $this->validate();

        try {
            $subscription = $this->createSubscription();
            $this->createPayment($subscription);

            if ($this->currentInstance) {
                $this->currentInstance->subscription()->update([
                    'status' => 'active',
                    'plan_id' => $this->plan->id,
                    'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth()
                ]);
            }

            session(['payment_completed' => true]);
            $this->alert('success', 'Paiement traité avec succès');
            return redirect()->route('instance.create');
        } catch(\Exception $e) {
            $this->alert('error', 'Erreur lors du paiement');
            logger()->error('Payment error:', ['error' => $e->getMessage()]);
        }
    }

   public function submit()
{
    dd('Form submitted', $this->paymentMethod, $this->cardInfo, $this->mobileNumber);
    $this->processPayment();
}

   protected function createSubscription()
   {
       return Subscription::create([
           'user_id' => Auth::id(),
           'plan_id' => $this->plan->id,
           'start_date' => now(),
           'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
           'status' => 'active'
       ]);
   }

   protected function createPayment($subscription)
   {
       return Payment::create([
           'user_id' => Auth::id(),
           'plan_id' => $this->plan->id,
           'subscription_id' => $subscription->id,
           'amount' => $this->calculateTotal(),
           'status' => 'completed',
           'payment_method' => $this->paymentMethod,
           'cardholder_name' => $this->paymentMethod === 'VISA' ? $this->cardInfo['name'] : null,
           'transaction_id' => 'TXN_' . uniqid()
       ]);
   }

   public function updatedIsAnnual()
    {
        $this->dispatch('priceUpdated', [
            'total' => $this->calculateTotal()
        ]);
    }


    protected function calculateTotal()
    {
        $basePrice = $this->isAnnual ? $this->plan->price_yearly : $this->plan->price_monthly;

        if ($this->selectedSubPlan) {
            $subPlanPrice = $this->isAnnual ?
                $this->selectedSubPlan->price_yearly/12 :
                $this->selectedSubPlan->price_monthly;
            $basePrice += $subPlanPrice;
        }

        return $basePrice;
    }



   public function render()
   {
       return view('livewire.payment.payment-process')->layout('layouts.main');
   }
}
