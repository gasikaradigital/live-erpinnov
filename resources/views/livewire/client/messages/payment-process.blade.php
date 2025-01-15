<!-- resources/views/livewire/client/payment-process.blade.php -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="shadow-sm card">
        <div class="row g-0">
            <div class="col-lg-7 border-end">
                <div class="p-4 card-body">
                    <h4 class="mb-3">Ajoutez une nouvelle carte pour continuer</h4>

                    @if(!$plan)
                        <p class="text-danger">Plan non trouvé. Veuillez sélectionner un plan valide.</p>
                    @else
                        <form id="payment-form" wire:submit.prevent="processPayment">
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Numéro de carte</label>
                                <input type="text" class="form-control" id="cardNumber" wire:model.defer="cardNumber" placeholder="1234 5678 9012 3456">
                                @error('cardNumber') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="cardholderName" class="form-label">Nom du titulaire de la carte</label>
                                    <input type="text" class="form-control" id="cardholderName" wire:model.defer="cardholderName" placeholder="John Doe">
                                    @error('cardholderName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="cardExpiry" class="form-label">Date d'expiration</label>
                                    <input type="text" class="form-control" id="cardExpiry" wire:model.defer="cardExpiry" placeholder="MM/YY">
                                    @error('cardExpiry') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="cardCVC" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cardCVC" wire:model.defer="cardCVC" placeholder="123" maxlength="3">
                                    @error('cardCVC') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 card-body">
                    <h4 class="mb-3">Résumé des paiements</h4>
                    @if($plan)
                        <div class="p-3 mb-3 rounded bg-light">
                            <h4 class="mb-1 fw-bold text-dark">Offre {{ $plan->name }}</h4>
                            <h5 class="card-subtitle mb-2 text-muted">
                                @if($isAnnual)
                                    {{ number_format($plan->price_yearly / 12, 2) }}€ <small>/mois</small>
                                    <br><small class="text-success">(Facturé annuellement: {{ number_format($plan->price_yearly, 2) }}€)</small>
                                @else
                                    {{ number_format($plan->price_monthly, 2) }}€ <small>/mois</small>
                                @endif
                            </h5>
                            <button type="button" data-bs-target="#pricingModal" data-bs-toggle="modal" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-exchange-alt me-1"></i>Changer de plan
                            </button>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="annualBillingSwitch"
                                wire:model.live="isAnnual">
                                <label class="form-check-label" for="annualBillingSwitch">
                                    Facturation annuelle (économisez 10%)
                                </label>
                                @if($isAnnual)
                                    <p class="text-success mt-2">
                                        <i class="fas fa-piggy-bank me-1"></i>Vous économisez {{ number_format($yearlySavings, 2) }}€ par an !
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Total à payer</strong>
                                <strong>{{ number_format($total, 2) }}€</strong>
                                <small>
                                    @if($isAnnual)
                                        Abonnement annuel
                                    @else
                                        Abonnement mensuel
                                    @endif
                                </small>
                            </div>
                        </div>
                        <button type="submit" form="payment-form" class="btn btn-success mb-3">
                            Traiter le paiement
                        </button>
                    @else
                        <p class="text-danger">Aucun plan sélectionné.</p>
                    @endif
                    <small class="mt-0 d-block text-muted">
                        Une fois votre paiement traité, votre compte sera immédiatement mis à niveau pour
                        {{ $isAnnual ? '1 an' : '1 mois' }}.
                    </small>
                    <small class="d-block text-muted text-center">
                        <i class="fas fa-shield-alt me-1"></i>Paiement sécurisé
                    </small>
                </div>
            </div>
        </div>
    </div>
    <livewire:client.subscription-plans />
</div>

<!-- Script pour déclencher le processus de paiement -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var paymentButton = document.getElementById('payment-button');
        var paymentForm = document.getElementById('payment-form');

        if (paymentButton && paymentForm) {
            paymentButton.addEventListener('click', function() {
                paymentForm.dispatchEvent(new Event('submit'));
            });
        }
    });
</script>
