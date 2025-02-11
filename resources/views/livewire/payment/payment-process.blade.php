{{-- payment-process.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 mt-16">
    @if($hasUsedTrial)
        <x-header-nav title="Mode de paiement "/>
    @endif
    {{-- Contenu principal avec padding ajusté --}}
    <main class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Colonne principale --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Carte d'essai gratuit --}}
                @if(!$hasUsedTrial)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            {{-- Contenu gauche --}}
                            <div class="space-y-4">
                                {{-- Badge et titre --}}
                                <div>
                                    <h2 class="mt-2 text-xl font-semibold text-gray-900">Essai gratuit de 15 jours</h2>
                                    <p class="text-sm text-gray-500">Découvrez toutes les fonctionnalités sans engagement</p>
                                </div>

                                {{-- Liste compacte --}}
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Accès complet à toutes les fonctionnalités</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Support prioritaire inclus</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Bouton à droite --}}
                            <div class="pl-6">
                                <button wire:click="startTrial"
                                    class="group inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Démarrer l'essai
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Section Paiement avec formulaires cachés --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200/50">
                    {{-- Méthodes de paiement --}}
                    <div class="p-4" x-data="{ selectedPayment: null }">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Moyens de paiement acceptés</h3>

                        {{-- Boutons de méthode de paiement --}}
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            @foreach(['VISA', 'OrangeMoney', 'Mvola'] as $method)
                                <button type="button"
                                    wire:click="$set('paymentMethod', '{{ $method }}')"
                                    @click="selectedPayment = selectedPayment === '{{ $method }}' ? null : '{{ $method }}'"
                                    class="relative p-4 rounded-lg border-2 transition-all duration-200
                                        {{ $paymentMethod === $method
                                            ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-100'
                                            : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                    <div class="flex flex-col items-center">
                                        <div class="mb-2 h-8 flex items-center justify-center">
                                            <img src="{{ asset('client/assets/img/logo/' . ($method === 'VISA' ? 'visa.svg' : ($method === 'OrangeMoney' ? 'OM.svg' : 'MVOLA.png'))) }}"
                                                alt="{{ $method }}"
                                                class="h-full w-auto object-contain">
                                        </div>
                                        <span class="text-sm font-medium {{ $paymentMethod === $method ? 'text-blue-600' : 'text-gray-700' }}">
                                            {{ $method }}
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        {{-- Formulaires --}}
                        <div x-show="selectedPayment"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="mt-6">
                            {{-- Formulaire VISA --}}
                            <div x-show="selectedPayment === 'VISA'">
                                @include('livewire.payment.forms.VISA')
                            </div>
                            {{-- Formulaire Orange Money --}}
                            <div x-show="selectedPayment === 'OrangeMoney'">
                                @include('livewire.payment.forms.OrangeMoney')
                            </div>
                            {{-- Formulaire Mvola --}}
                            <div x-show="selectedPayment === 'Mvola'">
                                @include('livewire.payment.forms.Mvola')
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Résumé (droite) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 bg-white rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-200/80 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900">Résumé de votre commande</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            {{-- Détails du plan --}}
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $plan->name }}</p>
                                    @if($selectedSubPlan)
                                        <p class="text-sm text-gray-500">{{ $selectedSubPlan->name }}</p>
                                    @endif
                                </div>
                                <p class="font-medium text-gray-900">{{ number_format($this->calculateTotal(), 2) }}€</p>
                            </div>

                            {{-- Économie annuelle --}}
                            @if($isAnnual)
                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <p class="ml-3 text-sm text-green-700 font-medium">
                                            Économie de 10% avec la facturation annuelle
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- Total --}}
                            <div class="pt-4 border-t border-gray-200/80">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-medium text-gray-700">Total</span>
                                    <span class="text-2xl font-bold text-gray-900">{{ number_format($this->calculateTotal(), 2) }}€</span>
                                </div>
                            </div>
                        </div>

                        {{-- Garanties et Sécurité --}}
                        <div class="mt-8 space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Paiement 100% sécurisé</h4>
                                    <p class="mt-1 text-sm text-gray-500">Vos données sont protégées et chiffrées</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Satisfait ou remboursé</h4>
                                    <p class="mt-1 text-sm text-gray-500">Garantie de remboursement de 30 jours</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Support client dédié</h4>
                                    <p class="mt-1 text-sm text-gray-500">Assistance disponible 24/7</p>
                                </div>
                            </div>
                        </div>

                        {{-- CGV et confidentialité --}}
                        <div class="mt-8 pt-6 border-t border-gray-200/80">
                            <p class="text-sm text-gray-500">
                                En procédant au paiement, vous acceptez nos
                                <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline">conditions générales</a>
                                et notre
                                <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline">politique de confidentialité</a>.
                            </p>
                        </div>
                    </div>

                    {{-- Bouton de paiement pour mobile --}}
                    {{-- <div class="p-4 bg-gray-50 border-t border-gray-200/80">
                        <button type="submit"
                            onclick="document.getElementById('{{ strtolower($paymentMethod) }}Form')?.requestSubmit()"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-medium
                                   hover:bg-blue-700 focus:outline-none focus:ring-2
                                   focus:ring-blue-500 focus:ring-offset-2 transition-colors
                                   disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$paymentMethod ? 'disabled' : '' }}>
                            @if(!$paymentMethod)
                                Sélectionnez un mode de paiement
                            @else
                                Payer {{ number_format($this->calculateTotal(), 2) }}€
                            @endif
                        </button>
                    </div> --}}
            </div>
        </div>
    </main>
</div>

@push('styles')
<style>
    /* Animations personnalisées */
    .group:hover img {
        transform: scale(1.05);
        transition: transform 0.2s ease-in-out;
    }

    /* Style pour le toggle de période */
    .peer:checked ~ .peer-checked\:after\:translate-x-full::after {
        transform: translateX(100%);
    }

    /* Animation pour le stepper */
    .stepper-line {
        transition: background-color 0.3s ease;
    }
</style>
@endpush
