<!-- resources/views/livewire/client/payment-process.blade.php -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-center items-center min-h-screen">
    <div class="max-w-7xl w-full mx-auto shadow-lg rounded-lg overflow-hidden">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-2/3 bg-white border-r">
                <div class="p-6">
                    <!-- Bouton de retour -->
                    <button onclick="window.history.back();" class="mb-4 bg-gray-300 btn-sm text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-400 transition">
                        <i class="fa fa-arrow-left mr-1"></i> Retour
                    </button>
                    <h4 class="mb-4 text-xl font-semibold">Sélectionnez un mode de paiement</h4>
                    <!-- Modes de paiement -->
                    <div class="mb-6 flex space-x-4">
                        <button wire:click="$set('paymentMethod', 'VISA')" class="px-4 py-2 rounded-lg flex items-center space-x-2 {{ $paymentMethod === 'VISA' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/visa.svg') }}" alt="VISA" class="w-10 h-10">
                            <span>VISA</span>
                        </button>
                        <button wire:click="$set('paymentMethod', 'OrangeMoney')" class="px-4 py-2 rounded-lg flex items-center space-x-2 {{ $paymentMethod === 'OrangeMoney' ? 'bg-orange-600 text-white' : 'bg-gray-300 text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/OM.svg') }}" alt="OrangeMoney" class="w-10 h-10">
                            <span>OrangeMoney</span>
                        </button>
                        <button wire:click="$set('paymentMethod', 'Mvola')" class="px-4 py-2 rounded-lg flex items-center space-x-2 {{ $paymentMethod === 'Mvola' ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-700' }}">
                            <img src="{{ asset('/client/assets/img/logo/MVOLA.png') }}" alt="Mvola" class="w-10 h-10">
                            <span>Mvola</span>
                        </button>
                    </div>

                    @if(!$plan)
                        <p class="text-red-500">Plan non trouvé. Veuillez sélectionner un plan valide.</p>
                    @else
                        <div class="w-full">
                            @if($paymentMethod === 'VISA')
                                <form id="payment-form" wire:submit.prevent="processPayment">
                                    <div class="mb-4">
                                        <label for="cardNumber" class="block text-sm font-medium text-gray-700">Numéro de carte</label>
                                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="cardNumber" wire:model.defer="cardNumber" placeholder="1234 5678 9012 3456">
                                        @error('cardNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex space-x-4">
                                        <div class="w-full">
                                            <label for="cardholderName" class="block text-sm font-medium text-gray-700">Nom du titulaire de la carte</label>
                                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="cardholderName" wire:model.defer="cardholderName" placeholder="John Doe">
                                            @error('cardholderName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="w-1/4">
                                            <label for="cardExpiry" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="cardExpiry" wire:model.defer="cardExpiry" placeholder="MM/YY">
                                            @error('cardExpiry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="w-1/4">
                                            <label for="cardCVC" class="block text-sm font-medium text-gray-700">CVV</label>
                                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="cardCVC" wire:model.defer="cardCVC" placeholder="123" maxlength="3">
                                            @error('cardCVC') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </form>
                            @elseif($paymentMethod === 'OrangeMoney' || $paymentMethod === 'Mvola')
                                <!-- Formulaire pour OrangeMoney et Mvola -->
                                <form id="payment-form" wire:submit.prevent="processPayment">
                                    <div class="mb-4">
                                        <label for="mobileNumber" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="mobileNumber" wire:model.defer="mobileNumber" placeholder="033 12 345 67">
                                        @error('mobileNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="w-full {{ $paymentMethod === 'OrangeMoney' ? 'bg-orange-600' : 'bg-green-600' }} text-white rounded-lg px-4 py-2 hover:bg-opacity-90 transition">
                                        Payer avec {{ $paymentMethod }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full lg:w-1/3 bg-gray-50">
                <div class="p-6">
                    <h4 class="mb-4 text-xl font-semibold">Résumé des paiements</h4>
                    @if($plan)
                        <div class="p-4 mb-4 bg-white rounded-lg shadow-md">
                            <h4 class="mb-2 font-bold text-gray-900">Offre {{ $plan->name }}</h4>
                            <h5 class="mb-2 text-gray-500">
                                @if($isAnnual)
                                    {{ number_format($plan->price_yearly / 12, 2) }} € <small>/mois</small>
                                    <br><small class="text-green-600">(Facturé annuellement: {{ number_format($plan->price_yearly, 2) }} €)</small>
                                @else
                                    {{ number_format($plan->price_monthly, 2) }} € <small>/mois</small>
                                @endif
                            </h5>
                            <button type="button" wire:click="changePlanModal" class="mt-2 text-indigo-600 border border-indigo-600 rounded-lg px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                                <i class="fas fa-exchange-alt mr-1"></i> Changer de plan
                            </button>
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="annualBillingSwitch" wire:model="isAnnual" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="annualBillingSwitch" class="ml-3 text-sm text-gray-700">Facturation annuelle (économisez 10%)</label>
                            </div>
                            @if($isAnnual)
                                <p class="mt-2 text-green-600">
                                    <i class="fas fa-piggy-bank mr-1"></i> Vous économisez {{ number_format($yearlySavings, 2) }} € par an !
                                </p>
                            @endif
                        </div>
                        <div class="mb-4 flex justify-between items-center">
                            <span class="text-lg font-semibold">Total à payer</span>
                            <span class="text-lg font-semibold">{{ number_format($total, 2) }} €</span>
                            <small class="text-gray-500">
                                @if($isAnnual)
                                    Abonnement annuel
                                @else
                                    Abonnement mensuel
                                @endif
                            </small>
                        </div>
                        <button type="submit" form="payment-form" class="w-full bg-green-600 text-white rounded-lg px-4 py-2 hover:bg-green-700 transition">
                            Traiter le paiement
                        </button>
                    @else
                        <p class="text-red-500">Aucun plan sélectionné.</p>
                    @endif
                    <small class="mt-4 block text-gray-500">
                        Une fois votre paiement traité, votre compte sera immédiatement mis à niveau pour
                        {{ $isAnnual ? '1 an' : '1 mois' }}.
                    </small>
                    <small class="block text-center text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i> Paiement sécurisé
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
