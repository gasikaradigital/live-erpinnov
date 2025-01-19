<!-- resources/views/livewire/client/payment-process.blade.php -->
<div class="tw-container tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8 tw-flex tw-justify-center tw-items-center tw-min-h-screen">
    <div class="tw-max-w-7xl tw-w-full tw-mx-auto tw-shadow-lg tw-rounded-lg tw-overflow-hidden">
        <div class="tw-flex tw-flex-wrap">
            <div class="tw-w-full lg:tw-w-2/3 tw-bg-white tw-border-r">
                <div class="tw-p-6">
                    <!-- Bouton de retour -->
                    <button onclick="window.history.back();" class="tw-mb-4 tw-bg-gray-300 btn-sm tw-text-gray-700 tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-gray-400 tw-transition">
                        <i class="fa fa-arrow-left tw-mr-1"></i> Retour
                    </button>
                    <h4 class="tw-mb-4 tw-text-xl tw-font-semibold">Sélectionnez un mode de paiement</h4>
                    <!-- Modes de paiement -->
                    <div class="tw-mb-6 tw-flex tw-space-x-4">
                        <button wire:click="$set('paymentMethod', 'VISA')" class="tw-px-4 tw-py-2 tw-rounded-lg tw-flex tw-items-center tw-space-x-2 {{ $paymentMethod === 'VISA' ? 'tw-bg-blue-600 tw-text-white' : 'tw-bg-gray-300 tw-text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/VISA.svg') }}" alt="VISA" class="tw-w-10 tw-h-10">
                            <span>VISA</span>
                        </button>
                        <button wire:click="$set('paymentMethod', 'OrangeMoney')" class="tw-px-4 tw-py-2 tw-rounded-lg tw-flex tw-items-center tw-space-x-2 {{ $paymentMethod === 'OrangeMoney' ? 'tw-bg-orange-600 tw-text-white' : 'tw-bg-gray-300 tw-text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/OM.svg') }}" alt="OrangeMoney" class="tw-w-10 tw-h-10">
                            <span>OrangeMoney</span>
                        </button>
                        <button wire:click="$set('paymentMethod', 'Mvola')" class="tw-px-4 tw-py-2 tw-rounded-lg tw-flex tw-items-center tw-space-x-2 {{ $paymentMethod === 'Mvola' ? 'tw-bg-green-600 tw-text-white' : 'tw-bg-gray-300 tw-text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/MVOLA.png') }}" alt="Mvola" class="tw-w-10 tw-h-10">
                            <span>Mvola</span>
                        </button>
                    </div>

                    @if(!$plan)
                        <p class="tw-text-red-500">Plan non trouvé. Veuillez sélectionner un plan valide.</p>
                    @else
                        <div class="tw-w-full">
                            @if($paymentMethod === 'VISA')
                                <form id="payment-form" wire:submit.prevent="processPayment">
                                    <div class="tw-mb-4">
                                        <label for="cardNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Numéro de carte</label>
                                        <input type="text" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm" id="cardNumber" wire:model.defer="cardNumber" placeholder="1234 5678 9012 3456">
                                        @error('cardNumber') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="tw-flex tw-space-x-4">
                                        <div class="tw-w-full">
                                            <label for="cardholderName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Nom du titulaire de la carte</label>
                                            <input type="text" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm" id="cardholderName" wire:model.defer="cardholderName" placeholder="John Doe">
                                            @error('cardholderName') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="tw-w-1/4">
                                            <label for="cardExpiry" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Date d'expiration</label>
                                            <input type="text" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm" id="cardExpiry" wire:model.defer="cardExpiry" placeholder="MM/YY">
                                            @error('cardExpiry') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="tw-w-1/4">
                                            <label for="cardCVC" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">CVV</label>
                                            <input type="text" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm" id="cardCVC" wire:model.defer="cardCVC" placeholder="123" maxlength="3">
                                            @error('cardCVC') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </form>
                            @elseif($paymentMethod === 'OrangeMoney' || $paymentMethod === 'Mvola')
                                <!-- Formulaire pour OrangeMoney et Mvola -->
                                <form id="payment-form" wire:submit.prevent="processPayment">
                                    <div class="tw-mb-4">
                                        <label for="mobileNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Numéro de téléphone</label>
                                        <input type="text" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm" id="mobileNumber" wire:model.defer="mobileNumber" placeholder="033 12 345 67">
                                        @error('mobileNumber') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="tw-w-full {{ $paymentMethod === 'OrangeMoney' ? 'tw-bg-orange-600' : 'tw-bg-green-600' }} tw-text-white tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-opacity-90 tw-transition">
                                        Payer avec {{ $paymentMethod }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="tw-w-full lg:tw-w-1/3 tw-bg-gray-50">
                <div class="tw-p-6">
                    <h4 class="tw-mb-4 tw-text-xl tw-font-semibold">Résumé des paiements</h4>
                    @if($plan)
                        <div class="tw-p-4 tw-mb-4 tw-bg-white tw-rounded-lg tw-shadow-md">
                            <h4 class="tw-mb-2 tw-font-bold tw-text-gray-900">Offre {{ $plan->name }}</h4>
                            <h5 class="tw-mb-2 tw-text-gray-500">
                                @if($isAnnual)
                                    {{ number_format($plan->price_yearly / 12, 2) }} € <small>/mois</small>
                                    <br><small class="tw-text-green-600">(Facturé annuellement: {{ number_format($plan->price_yearly, 2) }} €)</small>
                                @else
                                    {{ number_format($plan->price_monthly, 2) }} € <small>/mois</small>
                                @endif
                            </h5>
                            <button type="button" wire:click="changePlanModal" class="tw-mt-2 tw-text-indigo-600 tw-border tw-border-indigo-600 tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-indigo-600 hover:tw-text-white tw-transition">
                                <i class="fas fa-exchange-alt tw-mr-1"></i> Changer de plan
                            </button>
                        </div>
                        <div class="tw-mb-4">
                            <div class="tw-flex tw-items-center">
                                <input type="checkbox" id="annualBillingSwitch" wire:model="isAnnual" class="tw-rounded tw-border-gray-300 tw-text-indigo-600 focus:tw-ring-indigo-500">
                                <label for="annualBillingSwitch" class="tw-ml-3 tw-text-sm tw-text-gray-700">Facturation annuelle (économisez 10%)</label>
                            </div>
                            @if($isAnnual)
                                <p class="tw-mt-2 tw-text-green-600">
                                    <i class="fas fa-piggy-bank tw-mr-1"></i> Vous économisez {{ number_format($yearlySavings, 2) }} € par an !
                                </p>
                            @endif
                        </div>
                        <div class="tw-mb-4 tw-flex tw-justify-between tw-items-center">
                            <span class="tw-text-lg tw-font-semibold">Total à payer</span>
                            <span class="tw-text-lg tw-font-semibold">{{ number_format($total, 2) }} €</span>
                            <small class="tw-text-gray-500">
                                @if($isAnnual)
                                    Abonnement annuel
                                @else
                                    Abonnement mensuel
                                @endif
                            </small>
                        </div>
                        <button type="submit" form="payment-form" class="tw-w-full tw-bg-green-600 tw-text-white tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-green-700 tw-transition">
                            Traiter le paiement
                        </button>
                    @else
                        <p class="tw-text-red-500">Aucun plan sélectionné.</p>
                    @endif
                    <small class="tw-mt-4 tw-block tw-text-gray-500">
                        Une fois votre paiement traité, votre compte sera immédiatement mis à niveau pour
                        {{ $isAnnual ? '1 an' : '1 mois' }}.
                    </small>
                    <small class="tw-block tw-text-center tw-text-gray-500">
                        <i class="fas fa-shield-alt tw-mr-1"></i> Paiement sécurisé
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
