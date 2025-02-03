<?php
namespace App\Livewire\Payment;

use App\Models\Plan;
use App\Models\SubPlan;
use Livewire\Component;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PricingPlan extends Component
{
   use LivewireAlert;

   public $isYearly = true;
   public $currentPlan;
   public $selectedPlanId;
   public $selectedSubPlanId = null;

   public function mount()
   {
       $this->currentPlan = Auth::user()->activePlan();
   }

   public function selectSubPlan($planId, $subPlanId)
    {
        $this->selectedSubPlanId = $subPlanId;
        $this->dispatch('subPlanSelected', $subPlanId);
    }

    // PricingPlan.php
    public function selectPlan($uuid)
    {
    try {
        $user = Auth::user();
        $plan = Plan::where('uuid', $uuid)->firstOrFail();

        // Vérifier sélection sous-plan si nécessaire
        if ($plan->has_sub_plans && empty($this->selectedPlanId)) {
            $this->alert('error', 'Veuillez sélectionner une option');
            return;
        }

        $subPlan = null;
        if (!empty($this->selectedSubPlanId)) {
            $subPlan = SubPlan::find($this->selectedSubPlanId);
        }

        // Créer la souscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'sub_plan_id' => $subPlan?->id,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => $this->isYearly ? now()->addYear() : now()->addMonth()
        ]);

        if ($plan->is_free) {
            $this->alert('success', 'Plan gratuit activé!');
            return redirect()->route('instance.create');
        }

        return redirect()->route('payment.process', [
            'subscription_id' => $subscription->id
        ]);

    } catch (\Exception $e) {
        $this->alert('error', 'Erreur lors de la sélection du plan');
        logger()->error('Erreur plan:', ['error' => $e->getMessage()]);
    }
    }

   public function render()
   {
       return view('livewire.payment.pricing-plan', [
           'plans' => Plan::with('subPlans')
               ->orderBy('is_free', 'desc')
               ->orderBy('price_monthly')
               ->get()
       ])->layout('layouts.main');
   }
}
