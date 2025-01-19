<div class="tw-min-h-screen tw-bg-gradient-to-br tw-from-gray-50 tw-to-gray-100 dark:tw-from-gray-900 dark:tw-to-gray-800 tw-pb-20 tw-mt-16">
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8">
        <!-- En-tête et Stats -->
        @include('livewire.client.sections.header-stats')

        <!-- Navigation par onglets -->
        <div class="tw-mt-12" x-data="{ activeTab: 'instances' }">
            <!-- Tabs Navigation -->
            <div class="tw-bg-white/50 dark:tw-bg-gray-800/50 tw-backdrop-blur-xl tw-rounded-xl tw-p-2 tw-border tw-border-gray-200/50 dark:tw-border-gray-700/50 tw-shadow-sm">
                <nav class="tw-flex tw-space-x-2" aria-label="Tabs">
                    <button @click="activeTab = 'instances'"
                            :class="{
                                'tw-bg-white dark:tw-bg-gray-700 tw-shadow-sm tw-text-primary-600 dark:tw-text-primary-400': activeTab === 'instances',
                                'tw-text-gray-600 dark:tw-text-gray-300 hover:tw-text-gray-800 dark:hover:tw-text-gray-100 hover:tw-bg-white/50 dark:hover:tw-bg-gray-700/50': activeTab !== 'instances'
                            }"
                            class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-font-medium tw-text-sm tw-transition-all tw-duration-200">
                            <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Mes Instances
                    </button>
                    <button @click="activeTab = 'pricing'"
                            :class="{
                                'tw-bg-white dark:tw-bg-gray-700 tw-shadow-sm tw-text-primary-600 dark:tw-text-primary-400': activeTab === 'pricing',
                                'tw-text-gray-600 dark:tw-text-gray-300 hover:tw-text-gray-800 dark:hover:tw-text-gray-100 hover:tw-bg-white/50 dark:hover:tw-bg-gray-700/50': activeTab !== 'pricing'
                            }"
                            class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-font-medium tw-text-sm tw-transition-all tw-duration-200">
                            <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Abonnements
                    </button>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="tw-mt-8">
                <!-- Onglet Instances -->
                <div x-show="activeTab === 'instances'"
                     x-transition:enter="tw-transition tw-ease-out tw-duration-300"
                     x-transition:enter-start="tw-opacity-0 tw-transform tw-translate-y-4"
                     x-transition:enter-end="tw-opacity-100 tw-transform tw-translate-y-0"
                     x-cloak>
                    <div class="tw-grid lg:tw-grid-cols-12 tw-gap-8">
                        <!-- Colonne Principale -->
                        <div class="lg:tw-col-span-8 tw-space-y-6">
                            @include('livewire.client.sections.instances-list')
                        </div>

                        <!-- Colonne Latérale -->
                        <div class="lg:tw-col-span-4 tw-space-y-6">
                            <!-- Actions rapides -->
                            <div class="tw-bg-white dark:tw-bg-gray-800 tw-rounded-xl tw-shadow-sm tw-border tw-border-gray-200/50 dark:tw-border-gray-700/50 tw-overflow-hidden">
                                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200/50 dark:tw-border-gray-700/50">
                                    <h3 class=" tw-text-gray-900 dark:tw-text-white">Actions rapides</h3>
                                </div>
                                <div class="tw-p-3">
                                    <div class="tw-space-y-1">
                                        <button class="tw-group tw-w-full tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-text-gray-700 dark:tw-text-gray-300 tw-rounded-lg hover:tw-bg-gray-50 dark:hover:tw-bg-gray-700/50 tw-transition-colors">
                                            <span class="tw-p-2 tw-rounded-lg tw-bg-gray-100 dark:tw-bg-gray-700 tw-text-gray-600 dark:tw-text-gray-400 group-hover:tw-bg-primary-50 dark:group-hover:tw-bg-primary-900/20 group-hover:tw-text-primary-600 dark:group-hover:tw-text-primary-400 tw-transition-colors">
                                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </span>
                                            <span class="tw-font-medium">Entreprises</span>
                                        </button>
                                        <button class="tw-group tw-w-full tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-text-gray-700 dark:tw-text-gray-300 tw-rounded-lg hover:tw-bg-gray-50 dark:hover:tw-bg-gray-700/50 tw-transition-colors">
                                            <span class="tw-p-2 tw-rounded-lg tw-bg-gray-100 dark:tw-bg-gray-700 tw-text-gray-600 dark:tw-text-gray-400 group-hover:tw-bg-primary-50 dark:group-hover:tw-bg-primary-900/20 group-hover:tw-text-primary-600 dark:group-hover:tw-text-primary-400 tw-transition-colors">
                                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                            </span>
                                            <span class="tw-font-medium">Gérer la facturation</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques ou Informations supplémentaires -->
                            <div class="tw-bg-gradient-to-br tw-from-primary-500 tw-to-primary-600 tw-rounded-xl tw-shadow-sm tw-overflow-hidden">
                                <div class="tw-px-6 tw-py-8 tw-relative">
                                    <div class="tw-relative tw-z-10">
                                        <h4 class="tw-text-white tw-font-display tw-font-medium tw-mb-2">Ressources</h4>
                                        <p class="tw-text-primary-100 tw-text-sm">Accédez à notre documentation et nos guides pour tirer le meilleur parti de votre instance.</p>
                                        <a href="#" class="tw-inline-flex tw-items-center tw-gap-2 tw-mt-4 tw-text-white tw-text-sm tw-font-medium hover:tw-underline">
                                            En savoir plus
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="tw-absolute tw-right-0 tw-bottom-0 tw-transform tw-translate-x-1/4 tw-translate-y-1/4">
                                        <svg class="tw-w-32 tw-h-32 tw-text-white/10" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Abonnements -->
                <div x-show="activeTab === 'pricing'"
                     x-transition:enter="tw-transition tw-ease-out tw-duration-300"
                     x-transition:enter-start="tw-opacity-0 tw-transform tw-translate-y-4"
                     x-transition:enter-end="tw-opacity-100 tw-transform tw-translate-y-0"
                     x-cloak>
                    @include('livewire.client.sections.pricing-plans')
                </div>
            </div>
        </div>
    </div>
</div>
