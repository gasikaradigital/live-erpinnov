<!-- layout principal -->
<div class="tw-min-h-screen tw-bg-gray-50 tw-pb-20 tw-mt-16">
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8">
        <!-- En-tête et Stats -->
        @include('livewire.client.sections.header-stats')
        
        <!-- Navigation par onglets -->
        <div class="tw-mt-12" x-data="{ activeTab: 'instances' }">
            <div class="tw-border-b tw-border-gray-200">
                <nav class="tw-flex tw-space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'instances'"
                            :class="{'tw-border-indigo-500 tw-text-indigo-600': activeTab === 'instances',
                                    'tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300': activeTab !== 'instances'}"
                            class="tw-px-1 tw-py-4 tw-font-medium tw-text-sm tw-border-b-2 tw-whitespace-nowrap tw-transition-colors">
                        Mes Instances
                    </button>
                    <button @click="activeTab = 'pricing'"
                            :class="{'tw-border-indigo-500 tw-text-indigo-600': activeTab === 'pricing',
                                    'tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300': activeTab !== 'pricing'}"
                            class="tw-px-1 tw-py-4 tw-font-medium tw-text-sm tw-border-b-2 tw-whitespace-nowrap tw-transition-colors">
                        Abonnements
                    </button>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="tw-mt-8">
                <!-- Onglet Instances -->
                <div x-show="activeTab === 'instances'" x-cloak>
                    <div class="tw-grid lg:tw-grid-cols-12 tw-gap-8">
                        <!-- Colonne Principale -->
                        <div class="lg:tw-col-span-8">
                            <div class="tw-mb-8">
                                @include('livewire.client.sections.instances-list')
                            </div>
                        </div>

                        <!-- Colonne Latérale -->
                        <div class="lg:tw-col-span-4">
                            <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6 tw-mb-8">
                                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-4">Actions rapides</h3>
                                <div class="tw-space-y-3">
                                    <button class="tw-w-full tw-text-left tw-flex tw-items-center tw-px-4 tw-py-2 tw-text-gray-600 tw-rounded-lg hover:tw-bg-gray-50">
                                        <svg class="tw-w-5 tw-h-5 tw-mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Entreprises
                                    </button>
                                    <button class="tw-w-full tw-text-left tw-flex tw-items-center tw-px-4 tw-py-2 tw-text-gray-600 tw-rounded-lg hover:tw-bg-gray-50">
                                        <svg class="tw-w-5 tw-h-5 tw-mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Gérer la facturation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Onglet Abonnements -->
                <div x-show="activeTab === 'pricing'"
                    x-transition:enter="tw-transition tw-ease-out tw-duration-300"
                    x-transition:enter-start="tw-opacity-0 tw-transform tw-scale-95"
                    x-transition:enter-end="tw-opacity-100 tw-transform tw-scale-100"
                    x-cloak>
                    @include('livewire.client.sections.pricing-plans')
                </div>
            </div>
        </div>
    </div>
</div>