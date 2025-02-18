{{-- resources/views/livewire/payment/pricing-plan.blade.php --}}
<div class="bg-gray-50 min-h-screen py-12 mt-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- En-tête reste identique --}}
        <div class="text-center">
            <h1 class="text-xl font-bold tracking-tight text-gray-900">
                Plans tarifaires
            </h1>
            <p class="mt-3 text-lg text-gray-500">
                Choisissez l'offre qui correspond à vos besoins
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
        <div class="mt-6 space-y-4">
            @foreach($plans as $plan)
                {{-- Ajout de classes conditionnelles pour le flou et la désactivation --}}
                <div x-data="{ open: true }"
                     class="bg-white rounded-xl shadow-sm hover:shadow transition-all duration-200
                     {{ $plan->name !== 'Solo' ? 'opacity-50 blur-[3px] pointer-events-none select-none' : '' }}">
                    <div @click="open = !open"
                         class="p-4 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $plan->description }}</p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    @if(!$plan->is_free)
                                        <p class="text-xl font-bold text-gray-900">
                                            @if($plan->name === 'Solo')
                                                {{ number_format($isYearly ? $plan->price_yearly/12 : $plan->price_monthly, 2) }}€
                                            @else
                                                **.**€
                                            @endif
                                            <span class="text-sm font-normal text-gray-500">/mois</span>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if($plan->name === 'Solo')
                                                {{ number_format($isYearly ? $plan->price_yearly : $plan->price_monthly * 12, 2) }}€ par an
                                            @else
                                                ***.**€ par an
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-xl font-bold text-gray-900">Gratuit</p>
                                    @endif
                                </div>
                                @if($plan->name === 'Solo')
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                         :class="{'rotate-180': open}"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Contenu détaillé uniquement pour Solo --}}
                    @if($plan->name === 'Solo')
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="border-t border-gray-100">
                            {{-- Le reste du contenu détaillé reste identique --}}
                            <div class="p-4">
                                {{-- Features en colonnes --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    {{-- Liste des fonctionnalités --}}
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Fonctionnalités incluses</h4>
                                        <ul class="space-y-2">
                                            @foreach($plan->features as $feature)
                                                <li class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                    </svg>
                                                    <span class="ml-2 text-gray-600">{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    {{-- Sous-plans si disponibles --}}
                                    @if($plan->has_sub_plans && $plan->subPlans->count() > 0)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Options disponibles</h4>
                                            <div class="space-y-3">
                                                @foreach($plan->subPlans as $subPlan)
                                                    <label class="block p-3 rounded-lg border cursor-pointer transition-all
                                                        {{ $selectedSubPlanId == $subPlan->id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-200' }}">
                                                        <div class="flex items-center">
                                                            <input type="radio"
                                                                name="sub_plan_{{ $plan->id }}"
                                                                value="{{ $subPlan->id }}"
                                                                wire:model="selectedSubPlanId"
                                                                wire:change="selectSubPlan({{ $plan->id }}, {{ $subPlan->id }})"
                                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                            <div class="ml-2 flex-1">
                                                                <div class="flex justify-between items-center">
                                                                    <p class="text-sm font-medium text-gray-900">{{ $subPlan->name }}</p>
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        {{ number_format($isYearly ? $subPlan->price_yearly/12 : $subPlan->price_monthly, 2) }}€
                                                                        <span class="text-xs text-gray-500">/mois</span>
                                                                    </p>
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
                                <div class="mt-4">
                                    @if($plan->has_sub_plans)
                                        <div wire:key="plan-button-{{ $plan->id }}">
                                            @if(!$selectedSubPlanId)
                                                <button disabled
                                                    class="w-full py-2 px-4 rounded-lg text-center text-sm font-medium
                                                        bg-gray-100 text-gray-400 cursor-not-allowed">
                                                    Sélectionnez une option
                                                </button>
                                            @else
                                                <button type="button"
                                                    wire:click="selectPlan('{{ $plan->uuid }}')"
                                                    class="w-full py-2 px-4 rounded-lg text-center text-sm font-medium
                                                        bg-blue-600 hover:bg-blue-700 text-white transition-all duration-200">
                                                    Continuer
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <button type="button"
                                            wire:click="selectPlan('{{ $plan->uuid }}')"
                                            class="w-full py-2 px-4 rounded-lg text-center text-sm font-medium
                                                {{ $plan->is_default ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }}
                                                transition-all duration-200">
                                            {{ $plan->is_free ? 'Commencer gratuitement' : 'Sélectionner' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Bannière de garantie reste identique --}}
        <div class="mt-12 text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white shadow-sm">
                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="ml-2 text-xs font-medium text-gray-900">
                    Satisfait ou remboursé pendant 30 jours
                </span>
            </div>
        </div>
    </div>
</div>
