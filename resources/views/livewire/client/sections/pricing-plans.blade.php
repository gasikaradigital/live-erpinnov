<!-- pricing-plans.blade.php -->
<div class="tw-max-w-4xl tw-mx-auto tw-px-4 tw-pb-8" x-data="{ 
    isYearly: true,
    openPlan: null,
    animating: false 
}">
    <!-- Header -->
    <div class="tw-mb-10 tw-text-center">
        <h2 class="tw-text-3xl tw-font-bold tw-text-gray-900">Nos offres</h2>
        <p class="tw-mt-2 tw-text-gray-600">Choisissez le plan qui correspond à vos besoins</p>
        
        <!-- Toggle Prix -->
        <div class="tw-mt-6 tw-inline-flex tw-items-center tw-p-1 tw-bg-gray-100 tw-rounded-lg">
            <button @click="isYearly = false"
                    :class="!isYearly ? 'tw-bg-white tw-shadow-sm' : 'hover:tw-bg-gray-50'"
                    class="tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-md tw-transition-all">
                Mensuel
            </button>
            <button @click="isYearly = true"
                    :class="isYearly ? 'tw-bg-white tw-shadow-sm' : 'hover:tw-bg-gray-50'"
                    class="tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-md tw-transition-all tw-flex tw-items-center tw-gap-2">
                Annuel
                <span class="tw-bg-green-100 tw-text-green-800 tw-text-xs tw-px-2 tw-py-0.5 tw-rounded-full">-10%</span>
            </button>
        </div>
    </div>

    <!-- Plans Accordion -->
    <div class="tw-space-y-4">
        @foreach($plans->where('is_free', false) as $plan)
            <div class="tw-group"
                 x-data="{ 
                     isOpen: false,
                     init() { 
                         if ('{{ $plan->name }}' === 'Solo+') this.isOpen = true;
                     }
                 }">
                <!-- Plan Header Bar -->
                <div @click="isOpen = !isOpen"
                     class="tw-relative tw-bg-white tw-rounded-xl tw-shadow-sm tw-transition-all tw-duration-300 
                            tw-cursor-pointer tw-overflow-hidden hover:tw-shadow-md
                            {{ $plan->name === 'Solo+' ? 'tw-border-2 tw-border-gray-900' : 'tw-border tw-border-gray-200' }}">
                    
                    <!-- Background Progress Bar (Solo+ uniquement) -->
                    @if($plan->name === 'Solo+')
                        <div class="tw-absolute tw-inset-0 tw-bg-gray-50 tw-transition-all tw-duration-500"
                             :class="isOpen ? 'tw-w-full' : 'tw-w-0'"></div>
                    @endif

                    <!-- Content -->
                    <div class="tw-relative tw-flex tw-items-center tw-justify-between tw-p-6">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <!-- Icon -->
                            <div class="tw-flex-shrink-0 tw-w-12 tw-h-12 tw-rounded-lg tw-flex tw-items-center tw-justify-center
                                        {{ $plan->name === 'Solo+' ? 'tw-bg-gray-900 tw-text-white' : 'tw-bg-gray-100 tw-text-gray-600' }}">
                                @if($plan->name === 'Solo')
                                    <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @elseif($plan->name === 'Solo+')
                                    <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                @else
                                    <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Plan Info -->
                            <div>
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">{{ $plan->name }}</h3>
                                    @if($plan->name === 'Solo+')
                                        <span class="tw-bg-yellow-100 tw-text-yellow-800 tw-text-xs tw-px-2 tw-py-0.5 tw-rounded-full">
                                            Populaire
                                        </span>
                                    @endif
                                </div>
                                <p class="tw-text-sm tw-text-gray-500 tw-mt-1">{{ $plan->description }}</p>
                            </div>
                        </div>

                        <!-- Prix et Toggle -->
                        <div class="tw-flex tw-items-center tw-gap-6">
                            <div class="tw-text-right">
                                <div x-show="isYearly" x-transition>
                                    <div class="tw-text-2xl tw-font-bold tw-text-gray-900">
                                        {{ number_format($plan->price_yearly / 12, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="tw-text-sm tw-text-gray-500">par mois</div>
                                </div>
                                <div x-show="!isYearly" x-transition>
                                    <div class="tw-text-2xl tw-font-bold tw-text-gray-900">
                                        {{ number_format($plan->price_monthly, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="tw-text-sm tw-text-gray-500">par mois</div>
                                </div>
                            </div>
                            
                            <svg class="tw-w-5 tw-h-5 tw-text-gray-400 tw-transition-transform tw-duration-300"
                                 :class="isOpen ? 'tw-rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contenu déplié -->
                <div x-show="isOpen"
                     x-collapse
                     x-cloak
                     class="tw-bg-white tw-border-x tw-border-b tw-border-gray-200 tw-rounded-b-xl tw-shadow-sm tw-overflow-hidden">
                    <div class="tw-p-6 tw-space-y-6">
                        <!-- Prix annuel si applicable -->
                        <div x-show="isYearly" 
                             class="tw-bg-green-50 tw-rounded-lg tw-p-4">
                            <div class="tw-flex tw-justify-between tw-items-center">
                                <div>
                                    <p class="tw-text-green-700 tw-font-medium">Prix annuel</p>
                                    <p class="tw-text-green-600 tw-text-sm tw-mt-1">
                                        {{ number_format($plan->price_yearly, 0, ',', ' ') }} Ar/an
                                    </p>
                                </div>
                                <span class="tw-bg-green-100 tw-text-green-800 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm">
                                    Économisez {{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 0, ',', ' ') }} Ar
                                </span>
                            </div>
                        </div>

                        <!-- Fonctionnalités -->
                        <div class="tw-grid md:tw-grid-cols-2 tw-gap-6">
                            @foreach($plan->features as $feature)
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <svg class="tw-w-5 tw-h-5 tw-text-green-500 tw-flex-shrink-0" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="tw-text-gray-600 tw-text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Support -->
                        <div class="tw-flex tw-justify-between tw-items-center tw-p-4 tw-bg-gray-50 tw-rounded-lg">
                            <div>
                                <h4 class="tw-font-medium tw-text-gray-900">Support inclus</h4>
                                <p class="tw-text-sm tw-text-gray-500 tw-mt-1">
                                    {{ $plan->name === 'Solo' ? 'Email uniquement' : 
                                       ($plan->name === 'Solo+' ? 'Email & Messagerie' : 'Email, Chat & Visio') }}
                                </p>
                            </div>
                            <span class="tw-bg-gray-200 tw-text-gray-700 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm">
                                {{ $plan->name === 'Solo' ? 'Délai 48h' : 
                                   ($plan->name === 'Solo+' ? 'Délai 24h' : 'Prioritaire') }}
                            </span>
                        </div>

                        <!-- Bouton d'action -->
                        <button wire:click="changePlan('{{ $plan->uuid }}')"
                                class="tw-w-full tw-text-center tw-px-6 tw-py-3 tw-rounded-lg tw-font-medium tw-transition-all
                                       {{ $plan->name === 'Solo+' 
                                          ? 'tw-bg-gray-900 tw-text-white hover:tw-bg-gray-800' 
                                          : 'tw-border tw-border-gray-300 tw-text-gray-700 hover:tw-bg-gray-50' }}">
                            {{ $plan->name === 'Solo+' ? "Choisir l'offre recommandée" : 'Sélectionner ce plan' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Current Plan Badge -->
    @if($currentPlan)
        <div class="tw-mt-8 tw-mb-8 tw-text-center"> <!-- Ajout d'une marge en bas -->
            <span class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-gray-100 tw-text-gray-700 
                        tw-px-4 tw-py-2 tw-rounded-full tw-text-sm">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Plan actuel : {{ $currentPlan->name }}
            </span>
        </div>
    @endif
</div>