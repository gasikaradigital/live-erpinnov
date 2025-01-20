<!-- pricing-plans.blade.php -->
<div class="max-w-4xl mx-auto px-4 pb-8" x-data="{
    isYearly: true,
    openPlan: null}">
    <!-- Header -->
    <div class="mb-3 text-center">
        <h2 class="text-xl font-normal text-gray-900 dark:text-white">Nos offres</h2>
        <p class="mt-0 text-base text-gray-500 dark:text-gray-400">Choisissez le plan qui correspond à vos besoins</p>
        <!-- Toggle Prix -->
        <div class="mt-0 inline-flex items-center bg-gray-100 dark:bg-gray-800 p-1 rounded-full">
            <button @click="isYearly = false"
                    :class="!isYearly ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                    class="px-4 py-1.5 text-sm rounded-full transition-all">
                Mensuel
            </button>
            <button @click="isYearly = true"
                    :class="isYearly ? 'bg-white dark:bg-gray-700 shadow-sm' : ''"
                    class="px-4 py-1.5 text-sm rounded-full transition-all flex items-center gap-2">
                Annuel
                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-0.5 rounded-full">-10%</span>
            </button>
        </div>
    </div>

    <!-- Plans Accordion -->
    <div class="space-y-3">
        @foreach($plans->where('is_free', false) as $plan)
            <div class="group" x-data="{
                isOpen: false,
                init() {
                    if ('{{ $plan->name }}' === 'Solo+') this.isOpen = true;
                }
            }">
                <!-- Plan Header -->
                <div @click="isOpen = !isOpen"
                     class="relative bg-white dark:bg-gray-800 rounded-xl transition-all duration-200
                            cursor-pointer overflow-hidden hover:shadow-md border border-gray-200 dark:border-gray-700
                            {{ $plan->name === 'Solo Standard' ? 'border-2 border-primary-600' : '' }}">

                    <!-- Content -->
                    <div class="relative flex items-start justify-between p-6">
                        <div>
                            <h3 class="text-xl font-normal text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $plan->description }}</p>
                            @if($plan->name === 'Solo Standard')
                                <span class="inline-block mt-2 bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">Populaire</span>
                            @endif
                        </div>

                        <!-- Prix et Toggle -->
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div x-show="isYearly" x-transition>
                                    <div class="text-xl font-normal text-gray-900 dark:text-white">
                                        {{ number_format($plan->price_yearly / 12, 2, ',', ' ') }} € / {{ number_format($plan->price_local / 12, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">par mois</div>
                                </div>
                                <div x-show="!isYearly" x-transition>
                                    <div class="text-xl font-normal text-gray-900 dark:text-white">
                                        {{ number_format($plan->price_monthly, 2, ',', ' ') }} € / {{ number_format($plan->price_local, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">par mois</div>
                                </div>
                            </div>

                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 mt-1.5"
                                 :class="isOpen ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contenu déplié -->
                <div x-show="isOpen"
                     x-collapse
                     x-cloak
                     class="bg-white dark:bg-gray-800 border-x border-b border-gray-200 dark:border-gray-700 rounded-b-2xl mt-px">

                    <div class="p-6 space-y-6">
                        <!-- Prix annuel -->
                        <div x-show="isYearly"
                             class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-900 dark:text-white font-normal">Prix annuel</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                        {{ number_format($plan->price_yearly, 2, ',', ' ') }} € / {{ number_format($plan->price_local, 0, ',', ' ') }} Ar/an
                                    </p>
                                </div>
                                <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-3 py-1 rounded-full text-sm">
                                    Économisez {{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 2, ',', ' ') }} € / {{ number_format(($plan->price_local) - $plan->price_yearly, 0, ',', ' ') }} Ar
                                </span>
                            </div>
                        </div>

                        <!-- Fonctionnalités -->
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($plan->features as $feature)
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Bouton d'action -->
                        <button wire:click="changePlan('{{ $plan->uuid }}')"
                                class="w-full text-center px-6 py-2.5 rounded-lg text-sm transition-all
                                       {{ $plan->name === 'Solo+'
                                          ? 'bg-primary-600 dark:bg-primary-500 text-white hover:bg-primary-700 dark:hover:bg-primary-600'
                                          : 'bg-indigo-600 dark:bg-indigo-500 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 border border-indigo-600' }}">
                            {{ $plan->name === 'Solo+' ? "Choisir l'offre recommandée" : 'Sélectionner ce plan' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Current Plan Badge -->
    @if($currentPlan)
        <div class="mt-8 text-center">
            <span class="inline-flex items-center gap-2 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-full text-sm border border-gray-200 dark:border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Plan actuel : {{ $currentPlan->name }}
            </span>
        </div>
    @endif
</div>
