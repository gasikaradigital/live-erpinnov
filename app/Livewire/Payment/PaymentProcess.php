<?php
namespace App\Livewire\Payment;

use Livewire\Component;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\SubPlan;
use App\Models\Instance;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentProcess extends Component
{
    use LivewireAlert;

    public $uuid;
    public $plan;
    public $selectedSubPlan;
    public $paymentMethod = Payment::METHOD_VISA;
    public $cardInfo = [
        'name' => '',
        'number' => '',
        'expiry' => '',
        'cvc' => ''
    ];
    public $mobileNumber;
    public $isAnnual = false;
    public $hasUsedTrial = true;
    public $currentInstance;
    public $instanceId;
    public $isUpgrade = false;
    public $isPlanChange = false;

    // Propriétés publiques pour passer les devises et prix à la vue
    public $primaryCurrency;
    public $secondaryCurrency;
    public $primaryPrice;
    public $secondaryPrice;

    public $upgradeType = null;

    public function setUpgradeType($type)
    {
        $this->upgradeType = $type;
        session(['upgrade_type' => $type]);
    }

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

    public function mount($uuid, $instance = null)
    {
        $this->uuid = $uuid;
        $this->instanceId = $instance;

        if ($this->instanceId) {
            // Mise à niveau depuis une instance existante (essai ou payante)
            try {
                $this->currentInstance = Instance::with(['subscription.plan.subPlans'])->findOrFail($this->instanceId);
                $this->plan = $this->currentInstance->subscription->plan;
                $this->uuid = $this->plan->uuid;
                $this->isUpgrade = true;

                logger()->info('Mount avec instance pour mise à niveau', [
                    'instance_id' => $this->instanceId,
                    'plan_uuid' => $this->plan->uuid,
                    'plan_name' => $this->plan->name,
                    'subscription_status' => $this->currentInstance->subscription->status,
                ]);

                // Charger le sous-plan existant ou un sous-plan par défaut pour la mise à niveau
                $this->selectedSubPlan = $this->currentInstance->subscription->subPlan ?? $this->plan->subPlans()->where('is_default', true)->first();
                if (!$this->selectedSubPlan) {
                    $this->selectedSubPlan = $this->plan->subPlans()->first(); // Prendre le premier sous-plan si aucun défaut
                }

                if ($this->selectedSubPlan) {
                    logger()->info('Sous-plan chargé pour la mise à niveau', [
                        'sub_plan_id' => $this->selectedSubPlan->id,
                        'sub_plan_name' => $this->selectedSubPlan->name,
                        'price_monthly' => $this->selectedSubPlan->price_monthly,
                        'price_yearly' => $this->selectedSubPlan->price_yearly,
                        'price_local' => $this->selectedSubPlan->price_local,
                    ]);
                } else {
                    throw new \Exception('Aucun sous-plan valide trouvé pour ce plan');
                }

                if (!$this->plan) {
                    throw new \Exception('Plan invalide');
                }

                // Vérifier si l’instance est en essai
                if ($this->currentInstance->subscription->status !== Subscription::STATUS_TRIAL) {
                    throw new \Exception('Cette instance n’est pas en période d’essai et ne peut pas être mise à niveau.');
                }

                // Définir les devises principales et secondaires en fonction de pays
                $isMadagascar = $this->currentInstance->pays === 0;
                $this->primaryCurrency = $isMadagascar ? 'MGA' : 'EUR';
                $this->secondaryCurrency = $isMadagascar ? 'EUR' : 'MGA';

                // Initialiser les prix pour éviter qu'ils ne soient à 0
                $this->primaryPrice = $this->calculateTotal($this->primaryCurrency);
                $this->secondaryPrice = $this->calculateTotal($this->secondaryCurrency);

                $this->hasUsedTrial = Auth::user()->subscriptions()->where('status', Subscription::STATUS_TRIAL)->exists();
            } catch (\Exception $e) {
                logger()->error('Erreur chargement instance pour mise à niveau:', ['error' => $e->getMessage()]);
                return redirect()->route('espaceClient')->with('error', 'Instance ou plan invalide : ' . $e->getMessage());
            }
        } else {
            // Nouvelle souscription (cas d’un nouvel utilisateur ou essai gratuit)
            $this->hasUsedTrial = !Subscription::where('user_id', Auth::id())
            ->where('status', Subscription::STATUS_TRIAL)
            ->exists();

            $this->currentInstance = Instance::where('user_id', Auth::id())
                ->whereHas('subscription', function($q) {
                    $q->where('status', Subscription::STATUS_TRIAL);
                })->first();

            $selectedPlan = session('selected_plan');
            if (!$selectedPlan || $selectedPlan['uuid'] !== $uuid) {
                logger()->warning('Plan non sélectionné ou UUID invalide', ['uuid' => $uuid, 'session_uuid' => $selectedPlan['uuid'] ?? null]);
                return redirect()->route('plans.selection')->with('error', 'Plan non sélectionné.');
            }

            try {
                $this->plan = Plan::with('subPlans')->where('uuid', $uuid)->firstOrFail();

                logger()->info('Mount sans instance', [
                    'uuid' => $uuid,
                    'plan_name' => $this->plan->name,
                ]);

                $subPlanId = request()->query('sub_plan');
                if ($subPlanId) {
                    $this->selectedSubPlan = $this->plan->subPlans->where('id', $subPlanId)->first();
                    if (!$this->selectedSubPlan) {
                        logger()->warning('Sous-plan invalide', ['sub_plan_id' => $subPlanId]);
                        return redirect()->route('plans.selection')->with('error', 'Sous-plan invalide.');
                    }
                    logger()->info('Sous-plan chargé', [
                        'sub_plan_id' => $subPlanId,
                        'sub_plan_name' => $this->selectedSubPlan->name,
                        'price_monthly' => $this->selectedSubPlan->price_monthly,
                        'price_yearly' => $this->selectedSubPlan->price_yearly,
                        'price_local' => $this->selectedSubPlan->price_local,
                    ]);
                } else {
                    // Charger un sous-plan par défaut pour une nouvelle souscription
                    $this->selectedSubPlan = $this->plan->subPlans()->where('is_default', true)->first();
                    if (!$this->selectedSubPlan) {
                        $this->selectedSubPlan = $this->plan->subPlans()->first(); // Prendre le premier sous-plan si aucun défaut
                    }
                    if ($this->selectedSubPlan) {
                        logger()->info('Sous-plan par défaut chargé', [
                            'sub_plan_id' => $this->selectedSubPlan->id,
                            'sub_plan_name' => $this->selectedSubPlan->name,
                            'price_monthly' => $this->selectedSubPlan->price_monthly,
                            'price_yearly' => $this->selectedSubPlan->price_yearly,
                            'price_local' => $this->selectedSubPlan->price_local,
                        ]);
                    } else {
                        throw new \Exception('Aucun sous-plan valide trouvé pour ce plan');
                    }
                }

                // Définir les devises principales et secondaires en fonction de l'instance par défaut
                $isMadagascar = $this->currentInstance && $this->currentInstance->pays === 0;
                $this->primaryCurrency = $isMadagascar ? 'MGA' : 'EUR';
                $this->secondaryCurrency = $isMadagascar ? 'EUR' : 'MGA';

                // Initialiser les prix pour éviter qu'ils ne soient à 0
                $this->primaryPrice = $this->calculateTotal($this->primaryCurrency);
                $this->secondaryPrice = $this->calculateTotal($this->secondaryCurrency);
            } catch (\Exception $e) {
                logger()->error('Erreur chargement plan:', ['error' => $e->getMessage()]);
                return redirect()->route('plans.selection')->with('error', 'Plan introuvable : ' . $e->getMessage());
            }
        }
    }

    public function processPayment()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Rechercher une instance existante
            $existingInstance = Instance::where('user_id', Auth::id())
                ->whereHas('subscription', function($query) {
                    $query->whereIn('status', [Subscription::STATUS_TRIAL, Subscription::STATUS_ACTIVE]);
                })
                ->first();

            $prices = $this->calculatePrices();

            if ($this->isUpgrade && $this->instanceId) {
                // Cas de mise à niveau d'une instance existante
                $existingSubscription = $this->currentInstance->subscription;
                if (!$existingSubscription || $existingSubscription->status !== Subscription::STATUS_TRIAL) {
                    throw new \Exception('Cette instance n\'est pas en période d\'essai et ne peut pas être mise à niveau.');
                }

                // Créer un paiement pour la mise à niveau
                $payment = Payment::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $this->plan->id,
                    'subscription_id' => $existingSubscription->id,
                    'amount' => $prices['eur'],
                    'amount_local' => $prices['local'],
                    'currency' => $this->primaryCurrency,
                    'status' => Payment::STATUS_PENDING,
                    'payment_method' => $this->paymentMethod,
                    'cardholder_name' => $this->paymentMethod === 'VISA' ? $this->cardInfo['name'] : null,
                    'transaction_id' => 'TXN_' . uniqid()
                ]);

                if ($this->processPaymentWithProvider()) {
                    $payment->update(['status' => Payment::STATUS_COMPLETED]);

                    // Mettre à jour la souscription existante
                    $existingSubscription->update([
                        'status' => Subscription::STATUS_ACTIVE,
                        'plan_id' => $this->plan->id,
                        'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
                        'start_date' => now(),
                        'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
                    ]);

                    $this->currentInstance->update(['status' => 'active']);
                }
            } elseif ($existingInstance) {
                // Cas de mise à jour d'une instance existante (non en cours de mise à niveau)
                $existingSubscription = $existingInstance->subscription;

                // Créer le paiement
                $payment = Payment::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $this->plan->id,
                    'subscription_id' => $existingSubscription->id,
                    'amount' => $prices['eur'],
                    'amount_local' => $prices['local'],
                    'currency' => $this->primaryCurrency,
                    'status' => Payment::STATUS_PENDING,
                    'payment_method' => $this->paymentMethod,
                    'cardholder_name' => $this->paymentMethod === 'VISA' ? $this->cardInfo['name'] : null,
                    'transaction_id' => 'TXN_' . uniqid()
                ]);

                if ($this->processPaymentWithProvider()) {
                    $payment->update(['status' => Payment::STATUS_COMPLETED]);

                    // Mettre à jour la souscription existante
                    $existingSubscription->update([
                        'plan_id' => $this->plan->id,
                        'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
                        'status' => Subscription::STATUS_ACTIVE,
                        'start_date' => now(),
                        'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
                    ]);

                    $existingInstance->update(['status' => 'active']);
                }
            } else {
                // Cas d'un nouvel abonnement
                $subscription = Subscription::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $this->plan->id,
                    'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
                    'start_date' => now(),
                    'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
                    'status' => Subscription::STATUS_ACTIVE,
                ]);

                // Créer un nouveau paiement
                $payment = Payment::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $this->plan->id,
                    'subscription_id' => $subscription->id,
                    'amount' => $prices['eur'],
                    'amount_local' => $prices['local'],
                    'currency' => $this->primaryCurrency,
                    'status' => Payment::STATUS_PENDING,
                    'payment_method' => $this->paymentMethod,
                    'cardholder_name' => $this->paymentMethod === 'VISA' ? $this->cardInfo['name'] : null,
                    'transaction_id' => 'TXN_' . uniqid()
                ]);

                if ($this->processPaymentWithProvider()) {
                    $payment->update(['status' => Payment::STATUS_COMPLETED]);
                }
            }

            DB::commit();
            session(['payment_completed' => true, 'trial_activated' => false]);

            $redirectRoute = ($this->isUpgrade || $existingInstance) ? 'espaceClient' : 'entreprise.create';
            $successMessage = ($this->isUpgrade || $existingInstance)
                ? 'Mise à jour de votre abonnement effectuée avec succès.'
                : 'Paiement effectué avec succès. Vous pouvez maintenant créer votre instance.';

            return redirect()->route($redirectRoute)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erreur paiement:', ['error' => $e->getMessage()]);
            $this->alert('error', 'Erreur lors du paiement. Veuillez réessayer.');
        }
    }

    protected function processPaymentWithProvider()
    {
        return true; // Simulation
    }

    protected function calculatePrices()
    {
        $primaryPrice = $this->calculateTotal($this->primaryCurrency);
        $secondaryPrice = $this->calculateTotal($this->secondaryCurrency);

        $conversionRate = 5000; // MGA/EUR

        $prices = [
            'eur' => $this->primaryCurrency === 'EUR' ? $primaryPrice : ($primaryPrice / $conversionRate), // Prix principal en EUR
            'local' => $this->primaryCurrency === 'MGA' ? $primaryPrice : ($primaryPrice * $conversionRate) // Prix principal en MGA
        ];

        logger()->info('Résultat de calculatePrices', [
            'is_annual' => $this->isAnnual,
            'primary_price' => $primaryPrice,
            'secondary_price' => $secondaryPrice,
            'prices' => $prices,
            'primary_currency' => $this->primaryCurrency,
            'secondary_currency' => $this->secondaryCurrency,
        ]);

        return $prices;
    }

    public function calculateTotal($currency = null)
    {
        if (!$this->plan) {
            logger()->warning('Plan non défini dans calculateTotal');
            return 0;
        }

        // Si un sous-plan est sélectionné, utiliser ses prix
        if ($this->selectedSubPlan) {
            $basePrice = $this->isAnnual ? $this->selectedSubPlan->price_monthly : $this->selectedSubPlan->price_monthly;
            $conversionRate = 5000; // MGA/EUR

            if ($currency === 'MGA' || ($currency === null && $this->primaryCurrency === 'MGA')) {
                $basePrice = $this->isAnnual ? ($this->selectedSubPlan->price_local * 12) : $this->selectedSubPlan->price_local;
                if ($this->isAnnual) {
                    // Appliquer une réduction de 10% sur le prix annuel en MGA
                    $basePrice *= 0.9; // Réduction de 10% (300,000 MGA → 270,000 MGA)
                }
            } else {
                // Pour EUR, appliquer la réduction de 10% sur le prix annuel
                $basePrice = $this->isAnnual ? $this->selectedSubPlan->price_yearly : $this->selectedSubPlan->price_monthly;
                if ($this->isAnnual) {
                    // Appliquer une réduction de 10% sur le prix annuel en EUR
                    $basePrice *= 0.9; // Réduction de 10% (60.00 EUR → 54.00 EUR)
                }
                // Convertir en MGA si nécessaire pour le prix secondaire
                if ($currency === 'MGA') {
                    $basePrice = $this->isAnnual ? ($this->selectedSubPlan->price_local * 12) : $this->selectedSubPlan->price_local;
                    if ($this->isAnnual) {
                        // Appliquer une réduction de 10% sur le prix annuel en MGA
                        $basePrice *= 0.9; // Réduction de 10% (300,000 MGA → 270,000 MGA)
                    }
                    $basePrice *= $conversionRate;
                }
            }
        } else {
            logger()->warning('Aucun sous-plan sélectionné, prix du plan principal non défini');
            return 10; // Prix par défaut temporaire (ajustez selon vos besoins)
        }

        logger()->info('Résultat de calculateTotal', [
            'is_annual' => $this->isAnnual,
            'base_price' => $basePrice,
            'plan_name' => $this->plan->name,
            'sub_plan_name' => $this->selectedSubPlan ? $this->selectedSubPlan->name : 'Aucun',
            'sub_plan_price_monthly' => $this->selectedSubPlan ? $this->selectedSubPlan->price_monthly : null,
            'sub_plan_price_yearly' => $this->selectedSubPlan ? $this->selectedSubPlan->price_yearly : null,
            'sub_plan_price_local' => $this->selectedSubPlan ? $this->selectedSubPlan->price_local : null,
            'currency' => $currency ?? $this->primaryCurrency,
        ]);

        return $basePrice ?? 0;
    }

    public function calculateLocalTotal()
    {
        return $this->calculateTotal($this->primaryCurrency); // Retourne le prix principal (MGA ou EUR selon pays)
    }

    public function startTrial()
    {
        if (!$this->plan) {
            logger()->error('Plan non défini dans startTrial.');
            $this->alert('error', 'Aucun plan sélectionné. Veuillez sélectionner un plan avant de démarrer l\'essai.');
            return;
        }

        // Double vérification de l'utilisation de l'essai
        if (Subscription::hasEverUsedTrial(Auth::id())) {
            logger()->warning('Utilisateur a déjà utilisé son essai gratuit', ['user_id' => Auth::id()]);
            $this->alert('error', 'Vous avez déjà utilisé votre période d\'essai. Veuillez choisir un abonnement payant.');
            return;
        }

        if (Subscription::hasActiveTrial(Auth::id())) {
            logger()->warning('Utilisateur a déjà un essai actif', ['user_id' => Auth::id()]);
            $this->alert('error', 'Vous avez déjà un essai gratuit en cours. Veuillez attendre sa fin ou passer à un abonnement payant.');
            return;
        }

        try {
            DB::beginTransaction();

            // Créer la souscription
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $this->plan->id,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'status' => Subscription::STATUS_TRIAL
            ]);

            // Stocker les informations importantes en session
            session([
                'selected_plan' => [
                    'uuid' => $this->plan->uuid,
                    'name' => $this->plan->name,
                    'is_free' => false,
                    'subscription_id' => $subscription->id,
                    'plan_id' => $this->plan->id
                ],
                'trial_activated' => true,
                'payment_completed' => true
            ]);

            DB::commit();
            logger()->info('Essai gratuit créé avec succès', [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id
            ]);

            return redirect()->route('entreprise.create');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erreur lors de la création de l\'essai gratuit:', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            $this->alert('error', 'Une erreur est survenue lors du démarrage de l\'essai gratuit. Veuillez réessayer.');
        }
    }

    protected function createSubscription()
    {
        // Vérifier si l'utilisateur a une souscription trial existante
        $existingSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', Subscription::STATUS_TRIAL)
            ->whereHas('plan', function ($query) {
                $query->where('is_free', true); // S'assurer que c'est un plan d'essai
            })
            ->first();

        if ($this->isUpgrade && $this->instanceId) {
            // Cas d'une mise à niveau d'une instance existante
            $instance = Instance::with('subscription')->findOrFail($this->instanceId);
            $subscription = $instance->subscription;

            $subscription->update([
                'plan_id' => $this->plan->id,
                'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
                'status' => Subscription::STATUS_ACTIVE,
                'start_date' => now(),
                'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
            ]);

            return $subscription;
        } elseif ($existingSubscription) {
            // Cas de transition d'un trial à un abonnement payant
            $existingSubscription->update([
                'plan_id' => $this->plan->id,
                'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
                'status' => Subscription::STATUS_ACTIVE,
                'start_date' => now(),
                'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
            ]);

            return $existingSubscription;
        }

        // Cas d'une nouvelle souscription sans trial préalable
        return Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $this->plan->id,
            'sub_plan_id' => $this->selectedSubPlan ? $this->selectedSubPlan->id : null,
            'start_date' => now(),
            'end_date' => $this->isAnnual ? now()->addYear() : now()->addMonth(),
            'status' => Subscription::STATUS_ACTIVE,
        ]);
    }

    protected function createPayment($subscription)
    {
        return Payment::create([
            'user_id' => Auth::id(),
            'plan_id' => $this->plan->id,
            'subscription_id' => $subscription->id,
            'amount' => $this->calculateTotal($this->primaryCurrency),
            'status' => 'completed',
            'payment_method' => $this->paymentMethod,
            'cardholder_name' => $this->paymentMethod === 'VISA' ? $this->cardInfo['name'] : null,
            'transaction_id' => 'TXN_' . uniqid()
        ]);
    }

    public function updatedIsAnnual()
    {
        // Forcer la mise à jour de la vue avec le nouveau prix principal et secondaire
        $this->primaryPrice = $this->calculateTotal($this->primaryCurrency);
        $this->secondaryPrice = $this->calculateTotal($this->secondaryCurrency);
        $this->dispatch('priceUpdated', [
            'primaryTotal' => $this->primaryPrice, // Envoyer le prix principal
            'secondaryTotal' => $this->secondaryPrice // Envoyer le prix secondaire
        ]);
        // Rafraîchir manuellement la vue pour s'assurer que les prix sont mis à jour en temps réel
        $this->render();
    }

    public function render()
    {
        return view('livewire.payment.payment-process', [
            'primaryCurrency' => $this->primaryCurrency,
            'secondaryCurrency' => $this->secondaryCurrency,
            'primaryPrice' => $this->primaryPrice ?? $this->calculateTotal($this->primaryCurrency),
            'secondaryPrice' => $this->secondaryPrice ?? $this->calculateTotal($this->secondaryCurrency),
            'hasUsedTrial' => $this->hasUsedTrial,
            'currentInstance' => $this->currentInstance,
            'remainingTrialDays' => $this->getRemainingTrialDaysProperty()
        ])->layout('layouts.main');
    }

    public function getRemainingTrialDaysProperty()
    {
        if ($this->currentInstance && $this->currentInstance->subscription && $this->currentInstance->subscription->status === Subscription::STATUS_TRIAL) {
            return now()->diffInDays($this->currentInstance->subscription->end_date);
        }
        return 0;
    }
}
