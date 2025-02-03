{{-- resources/views/livewire/payment/pricing-plan.blade.php --}}
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-xl font-extrabold tracking-tight text-gray-900 sm:text-xl">
                Plans tarifaires
            </h1>
            <p class="mt-2 text-lg text-gray-500">
                Choisissez l'offre qui correspond à vos besoins
            </p>

            {{-- Toggle annuel/mensuel --}}
            <div class="mt-12 inline-flex p-1.5 rounded-xl bg-white shadow-sm">
                <button wire:click="$set('isYearly', false)"
                        class="relative px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                        {{ !$isYearly ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-50' }}">
                    Mensuel
                </button>
                <button wire:click="$set('isYearly', true)"
                        class="relative px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                        {{ $isYearly ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-50' }}">
                    Annuel
                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        -10%
                    </span>
                </button>
            </div>
        </div>

        {{-- Grille des plans --}}
        <div class="mt-16 grid gap-8 lg:grid-cols-3 items-start">
            @foreach($plans as $plan)
                <div x-data="{ open: false }"
                     class="relative bg-white rounded-2xl shadow-lg transition-transform duration-200 hover:scale-[1.02]">
                    {{-- Badge plan populaire --}}
                    @if($plan->is_default)
                        <div class="absolute -top-4 inset-x-0">
                            <div class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold bg-blue-600 text-white">
                                Le plus populaire
                            </div>
                        </div>
                    @endif

                    <div class="p-8">
                        {{-- En-tête --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                <p class="mt-2 text-gray-500">{{ $plan->description }}</p>
                            </div>
                            <div class="text-right">
                                @if(!$plan->is_free)
                                    <p class="text-4xl font-extrabold text-gray-900">
                                        {{ number_format($isYearly ? $plan->price_yearly/12 : $plan->price_monthly, 2) }}€
                                        <span class="text-base font-medium text-gray-500">/mois</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ number_format($isYearly ? $plan->price_yearly : $plan->price_monthly * 12, 2) }}€ par an
                                    </p>
                                @else
                                    <p class="text-4xl font-extrabold text-gray-900">Gratuit</p>
                                @endif
                            </div>
                        </div>

                        {{-- Features --}}
                        <ul class="mt-8 space-y-4">
                            @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 p-0.5">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-gray-600">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Bouton Sélection --}}
                        @if(!$plan->has_sub_plans)
                            <a href="{{ route('payment.process', ['uuid' => $plan->uuid]) }}"
                               class="mt-8 block w-full py-3 px-6 text-center font-medium rounded-lg
                                      {{ $plan->is_default ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-900' }}
                                      transition duration-200">
                                {{ $plan->is_free ? 'Commencer gratuitement' : 'Sélectionner' }}
                            </a>
                        @else
                            {{-- Bouton accordéon --}}
                            <button @click="open = !open"
                                    class="mt-8 w-full inline-flex items-center justify-center px-6 py-3 font-medium rounded-lg
                                           {{ $plan->is_default ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-900' }}
                                           transition duration-200">
                                <span>Voir les options</span>
                                <svg :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transition-transform"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- Panneau sous-plans --}}
                    @if($plan->has_sub_plans && $plan->subPlans->count() > 0)
                        <div x-show="open" x-collapse class="border-t border-gray-100">
                            <div class="p-6 space-y-6">
                                @foreach($plan->subPlans as $subPlan)
                                    <label class="relative flex p-4 cursor-pointer rounded-lg hover:bg-gray-50
                                                {{ $selectedSubPlanId == $subPlan->id ? 'bg-blue-50 ring-2 ring-blue-600' : '' }}"
                                           :class="{'border-t': {{ !$loop->first }}}">
                                        <div class="flex items-center h-5">
                                            <input type="radio"
                                            name="sub_plan_{{ $plan->id }}"
                                            value="{{ $subPlan->id }}"
                                            wire:model="selectedSubPlanId"
                                            wire:change="selectSubPlan({{ $plan->id }}, {{ $subPlan->id }})"
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $subPlan->name }}</p>
                                                    <ul class="mt-2 space-y-1">
                                                        @foreach($subPlan->features as $feature)
                                                            <li class="flex text-sm text-gray-500">
                                                                <svg class="flex-shrink-0 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                                </svg>
                                                                <span class="ml-2">{{ $feature }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-lg font-semibold text-gray-900">
                                                        {{ number_format($isYearly ? $subPlan->price_yearly/12 : $subPlan->price_monthly, 2) }}€
                                                        <span class="text-sm font-normal text-gray-500">/mois</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach

                                <div class="pt-4 border-t">
                                    @if(empty($selectedSubPlanId))
                                        <button disabled
                                                class="w-full text-center px-6 py-3 font-medium rounded-lg bg-gray-300 opacity-50 cursor-not-allowed text-white">
                                            Sélectionnez une option
                                        </button>
                                    @else
                                        <a href="{{ route('payment.process', ['uuid' => $plan->uuid]) }}?sub_plan={{ $selectedSubPlanId }}"
                                           class="block w-full text-center px-6 py-3 font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition duration-200">
                                            Continuer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Garantie --}}
        <div class="mt-16 text-center">
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-white shadow-sm">
                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="ml-2 text-sm font-medium text-gray-900">
                    Satisfait ou remboursé pendant 30 jours
                </span>
            {{-- </div>
            @if($currentPlan)
                <p class="mt-4 text-sm text-gray-500">
                    Plan actuel : {{ $currentPlan->name }}
                </p>
            @endif --}}
        </div>
    </div>
 </div>
