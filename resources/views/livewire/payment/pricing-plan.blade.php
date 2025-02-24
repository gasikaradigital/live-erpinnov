{{-- resources/views/livewire/payment/pricing-plan.blade.php --}}
<div>
{{-- En-tête reste identique --}}
<div class="text-center">
    <h3 class="text-xl md:text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">
        Découvrez nos solutions
    </h3>
    <p class="mt-2 text-md text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
        Transformez votre entreprise avec nos offres sur mesure,
        <span class="font-medium text-blue-600 dark:text-blue-400">dès aujourd'hui</span>
    </p>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        Plus de {{ number_format(rand(1000, 9999)) }} entreprises nous font déjà confiance
    </p>

    {{-- Toggle annuel/mensuel reste identique --}}
    <div class="mt-4 inline-flex p-1 rounded-xl bg-white shadow-sm">
        <button wire:click="$set('isYearly', false)"
            class="relative px-6 py-2 text-sm font-medium rounded-lg transition-all duration-200
            {{ !$isYearly ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-50' }}">
            Mensuel
        </button>
        <button wire:click="$set('isYearly', true)"
            class="relative px-6 py-2 text-sm font-medium rounded-lg transition-all duration-200
            {{ $isYearly ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-50' }}">
            Annuel
            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                -10%
            </span>
        </button>
    </div>
</div>

{{-- Liste des plans --}}
<div class="mt-6 space-y-6">
    @foreach($plans as $plan)
        <div x-data="{ open: true }"
             class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300
                   {{ $plan->name !== 'Solo' ? 'opacity-50 blur-[3px] pointer-events-none select-none' : '' }}">
            {{-- En-tête du plan --}}
            <div @click="open = !open"
                 class="p-6 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-start justify-between">
                    {{-- Info du plan --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            @if($plan->is_free)
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Gratuit</span>
                            @endif
                        </div>
                        <p class="mt-2 text-gray-600">{{ $plan->description }}</p>
                    </div>
                    {{-- Prix --}}
                    @if($plan->name === 'Solo')
                        <div class="text-right">
                            <div class="flex flex-col items-end">
                                @if($plan->has_sub_plans && $plan->subPlans->count() > 0)
                                    @php
                                        $basicPlan = $plan->subPlans->where('is_default', true)->first();
                                    @endphp
                                    <div class="text-sm text-gray-500">À partir de</div>
                                    <div class="flex items-baseline mt-1">
                                        <span class="text-3xl font-bold text-gray-900">{{ number_format($isYearly ? $basicPlan->price_yearly/12 : $basicPlan->price_monthly, 2) }}€</span>
                                        <span class="ml-1 text-gray-500">/mois</span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        ou {{ number_format($basicPlan->price_local, 0) }} Ar/mois
                                    </div>
                                    @if($isYearly)
                                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Économisez 10% sur l'abonnement annuel
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Icône toggle --}}
                    <div class="ml-4">
                        <div class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-200"
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contenu détaillé --}}
            @if($plan->name === 'Solo')
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="border-t border-gray-100">
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-8">
                            {{-- Fonctionnalités --}}
                            <div class="space-y-6">
                                <h4 class="text-base font-semibold text-gray-900">Fonctionnalités incluses</h4>
                                <ul class="space-y-4">
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mt-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                            <span class="ml-3 text-gray-600">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Options --}}
                            @if($plan->has_sub_plans && $plan->subPlans->count() > 0)
                                <div class="space-y-6">
                                    <h4 class="text-base font-semibold text-gray-900">Options disponibles</h4>
                                    <div class="space-y-4">
                                        @foreach($plan->subPlans as $subPlan)
                                            <label class="block p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                                {{ $selectedSubPlanId == $subPlan->id ? 'border-blue-500 bg-blue-50/50' : 'border-gray-200 hover:border-blue-200' }}">
                                                <div class="flex items-start gap-4">
                                                    <input type="radio"
                                                           name="sub_plan_{{ $plan->id }}"
                                                           value="{{ $subPlan->id }}"
                                                           wire:model="selectedSubPlanId"
                                                           wire:change="selectSubPlan({{ $plan->id }}, {{ $subPlan->id }})"
                                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <p class="font-medium text-gray-900">{{ $subPlan->name }}</p>
                                                                <ul class="mt-2 space-y-1">
                                                                    @foreach($subPlan->features as $feature)
                                                                        <li class="text-sm text-gray-500">• {{ $feature }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="font-medium text-gray-900">
                                                                    {{ number_format($isYearly ? $subPlan->price_yearly/12 : $subPlan->price_monthly, 2) }}€
                                                                    <span class="text-sm text-gray-500">/mois</span>
                                                                </p>
                                                                <p class="text-sm text-gray-500">
                                                                    {{ number_format($subPlan->price_local, 0) }} Ar/mois
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                       {{-- Bouton d'action --}}
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            @if($plan->has_sub_plans)
                                <div class="flex justify-end" wire:key="plan-button-{{ $plan->id }}">
                                    @if(!$selectedSubPlanId)
                                        <button disabled
                                            class="inline-flex items-center px-8 py-3 text-sm font-medium rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                                            Sélectionnez une option
                                        </button>
                                    @else
                                        <button type="button"
                                            wire:click="selectPlan('{{ $plan->uuid }}')"
                                            class="inline-flex items-center px-8 py-3 rounded-xl bg-blue-600 text-white font-medium transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Continuer
                                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="flex justify-end">
                                    <button type="button"
                                        wire:click="selectPlan('{{ $plan->uuid }}')"
                                        class="inline-flex items-center px-8 py-3 rounded-xl {{ $plan->is_default ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} font-medium transition-all duration-200">
                                        {{ $plan->is_free ? 'Commencer gratuitement' : 'Continuer' }}
                                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
