<!-- pricing-plans.blade.php -->
<div class="tw-max-w-4xl tw-mx-auto tw-px-4 tw-pb-8" x-data="{
    isYearly: true,
    openPlan: null}">
    <!-- Header -->
    <div class="tw-mb-3 tw-text-center">
        <h2 class="tw-text-xl tw-font-normal tw-text-gray-900 dark:tw-text-white">Nos offres</h2>
        <p class="tw-mt-0 tw-text-base tw-text-gray-500 dark:tw-text-gray-400">Choisissez le plan qui correspond à vos besoins</p>
        <!-- Toggle Prix -->
        <div class="tw-mt-0 tw-inline-flex tw-items-center tw-bg-gray-100 dark:tw-bg-gray-800 tw-p-1 tw-rounded-full">
            <button @click="isYearly = false"
                    :class="!isYearly ? 'tw-bg-white dark:tw-bg-gray-700 tw-shadow-sm' : ''"
                    class="tw-px-4 tw-py-1.5 tw-text-sm tw-rounded-full tw-transition-all">
                Mensuel
            </button>
            <button @click="isYearly = true"
                    :class="isYearly ? 'tw-bg-white dark:tw-bg-gray-700 tw-shadow-sm' : ''"
                    class="tw-px-4 tw-py-1.5 tw-text-sm tw-rounded-full tw-transition-all tw-flex tw-items-center tw-gap-2">
                Annuel
                <span class="tw-bg-green-50 dark:tw-bg-green-900/30 tw-text-green-600 dark:tw-text-green-400 tw-text-xs tw-px-2 tw-py-0.5 tw-rounded-full">-10%</span>
            </button>
        </div>
    </div>

    <!-- Plans Accordion -->
    <div class="tw-space-y-3">
        @foreach($plans->where('is_free', false) as $plan)
            <div class="tw-group" x-data="{
                isOpen: false,
                init() {
                    if ('{{ $plan->name }}' === 'Solo+') this.isOpen = true;
                }
            }">
                <!-- Plan Header -->
                <div @click="isOpen = !isOpen"
                     class="tw-relative tw-bg-white dark:tw-bg-gray-800 tw-rounded-xl tw-transition-all tw-duration-200
                            tw-cursor-pointer tw-overflow-hidden hover:tw-shadow-md tw-border tw-border-gray-200 dark:tw-border-gray-700
                            {{ $plan->name === 'Solo Standard' ? 'tw-border-2 tw-border-primary-600' : '' }}">

                    <!-- Content -->
                    <div class="tw-relative tw-flex tw-items-start tw-justify-between tw-p-6">
                        <div>
                            <h3 class="tw-text-xl tw-font-normal tw-text-gray-900 dark:tw-text-white">{{ $plan->name }}</h3>
                            <p class="tw-mt-1 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">{{ $plan->description }}</p>
                            @if($plan->name === 'Solo Standard')
                                <span class="tw-inline-block tw-mt-2 tw-bg-yellow-200 tw-text-yellow-800 tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold">Populaire</span>
                            @endif
                        </div>

                        <!-- Prix et Toggle -->
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-text-right">
                                <div x-show="isYearly" x-transition>
                                    <div class="tw-text-xl tw-font-normal tw-text-gray-900 dark:tw-text-white">
                                        {{ number_format($plan->price_yearly / 12, 2, ',', ' ') }} € / {{ number_format($plan->price_local / 12, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">par mois</div>
                                </div>
                                <div x-show="!isYearly" x-transition>
                                    <div class="tw-text-xl tw-font-normal tw-text-gray-900 dark:tw-text-white">
                                        {{ number_format($plan->price_monthly, 2, ',', ' ') }} € / {{ number_format($plan->price_local, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">par mois</div>
                                </div>
                            </div>

                            <svg class="tw-w-5 tw-h-5 tw-text-gray-400 tw-transition-transform tw-duration-200 tw-mt-1.5"
                                 :class="isOpen ? 'tw-rotate-180' : ''"
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
                     class="tw-bg-white dark:tw-bg-gray-800 tw-border-x tw-border-b tw-border-gray-200 dark:tw-border-gray-700 tw-rounded-b-2xl tw-mt-px">

                    <div class="tw-p-6 tw-space-y-6">
                        <!-- Prix annuel -->
                        <div x-show="isYearly"
                             class="tw-bg-gray-50 dark:tw-bg-gray-700/50 tw-rounded-xl tw-p-4">
                            <div class="tw-flex tw-justify-between tw-items-center">
                                <div>
                                    <p class="tw-text-gray-900 dark:tw-text-white tw-font-normal">Prix annuel</p>
                                    <p class="tw-text-gray-500 dark:tw-text-gray-400 tw-text-sm tw-mt-1">
                                        {{ number_format($plan->price_yearly, 2, ',', ' ') }} € / {{ number_format($plan->price_local, 0, ',', ' ') }} Ar/an
                                    </p>
                                </div>
                                <span class="tw-bg-green-50 dark:tw-bg-green-900/30 tw-text-green-600 dark:tw-text-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm">
                                    Économisez {{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 2, ',', ' ') }} € / {{ number_format(($plan->price_local) - $plan->price_yearly, 0, ',', ' ') }} Ar
                                </span>
                            </div>
                        </div>

                        <!-- Fonctionnalités -->
                        <div class="tw-grid md:tw-grid-cols-2 tw-gap-4">
                            @foreach($plan->features as $feature)
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <svg class="tw-w-5 tw-h-5 tw-text-gray-400 dark:tw-text-gray-500 tw-flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="tw-text-gray-600 dark:tw-text-gray-300 tw-text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                        <!-- Bouton d'action -->
                        <button wire:click="changePlan('{{ $plan->uuid }}')"
                                class="tw-w-full tw-text-center tw-px-6 tw-py-2.5 tw-rounded-lg tw-text-sm tw-transition-all
                                       {{ $plan->name === 'Solo+'
                                          ? 'tw-bg-primary-600 dark:tw-bg-primary-500 tw-text-white hover:tw-bg-primary-700 dark:hover:tw-bg-primary-600'
                                          : 'tw-bg-indigo-600 dark:tw-bg-indigo-500 tw-text-white hover:tw-bg-indigo-700 dark:hover:tw-bg-indigo-600 tw-border tw-border-indigo-600' }}">
                            {{ $plan->name === 'Solo+' ? "Choisir l'offre recommandée" : 'Sélectionner ce plan' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Current Plan Badge -->
    @if($currentPlan)
        <div class="tw-mt-8 tw-text-center">
            <span class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-gray-50 dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300 tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-border tw-border-gray-200 dark:tw-border-gray-700">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Plan actuel : {{ $currentPlan->name }}
            </span>
        </div>
    @endif
</div>
