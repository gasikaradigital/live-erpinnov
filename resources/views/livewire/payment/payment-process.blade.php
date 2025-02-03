{{-- payment-process.blade.php --}}
<div>
<br><br><br>
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="grid lg:grid-cols-3 gap-0">
                {{-- Section principale --}}
                <div class="lg:col-span-2 p-8">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <a href="{{ route('plans.selection') }}" class="text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Retour aux plans
                            </a>
                            <h1 class="text-2xl font-bold">{{ $plan->name }}</h1>
                            @if($selectedSubPlan)
                                <p class="text-gray-600 mt-1">Option sélectionnée : {{ $selectedSubPlan->name }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Trial Card --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-8 border border-blue-100">
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">15 jours d'essai gratuit</h3>
                                <p class="mt-1 text-gray-600">Accédez à toutes les fonctionnalités sans engagement</p>
                                <button wire:click="startTrial" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Commencer l'essai
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Paiement --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-6">Ou souscrivez maintenant</h3>

                        {{-- Period Toggle --}}
                        <div class="flex items-center mb-8 bg-gray-50 p-4 rounded-lg">
                            <label class="flex items-center cursor-pointer">
                                <span class="relative">
                                    <input type="checkbox" wire:model="isAnnual" class="sr-only">
                                    <div class="w-10 h-6 bg-gray-300 rounded-full"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
                                </span>
                                <span class="ml-3 text-gray-700">Facturation annuelle (-10%)</span>
                            </label>
                        </div>

                        {{-- Payment Methods --}}
                        <div class="grid grid-cols-3 gap-6 mb-8">
                            @foreach(['VISA', 'OrangeMoney', 'Mvola'] as $method)
                                <button wire:click="$set('paymentMethod', '{{ $method }}')"
                                        class="relative p-6 rounded-xl border-2 transition-all duration-200
                                               {{ $paymentMethod === $method ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    @if($method === 'VISA')
                                        <img src="{{ asset('client/assets/img/logo/visa.svg') }}" alt="VISA" class="h-10 mx-auto mb-3">
                                    @elseif($method === 'OrangeMoney')
                                        <img src="{{ asset('client/assets/img/logo/OM.svg') }}" alt="OrangeMoney" class="h-10 mx-auto mb-3">
                                    @else
                                        <img src="{{ asset('client/assets/img/logo/MVOLA.png') }}" alt="Mvola" class="h-10 mx-auto mb-3">
                                    @endif
                                    <span class="block text-center font-medium">{{ $method }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Payment Form --}}
                        @include("livewire.payment.forms.$paymentMethod")
                    </div>
                </div>

                {{-- Résumé --}}
                <div class="bg-gray-50 p-8 border-l">
                    <h3 class="text-lg font-semibold mb-6">Résumé de la commande</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $plan->name }}</p>
                                @if($selectedSubPlan)
                                    <p class="text-sm text-gray-600">Option {{ $selectedSubPlan->name }}</p>
                                @endif
                            </div>
                            <span class="font-medium">{{ number_format($this->calculateTotal(), 2) }}€</span>
                        </div>

                        @if($isAnnual)
                            <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm">
                                Économie de 10% avec la facturation annuelle
                            </div>
                        @endif

                        <hr class="my-6">

                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span>{{ number_format($this->calculateTotal(), 2) }}€</span>
                        </div>

                        <p class="text-sm text-gray-500 mt-6">
                            En procédant au paiement, vous acceptez nos conditions générales.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .dot {
        transform: translateX(0);
        transition: transform 0.3s ease-in-out;
    }
    input:checked ~ .dot {
        transform: translateX(100%);
    }
    </style>
@endpush
</div>
