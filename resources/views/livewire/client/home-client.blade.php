<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 pb-20 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- En-tête et Stats -->
        @include('livewire.client.sections.header-stats')

        <!-- Navigation par onglets -->
        <div class="mt-5" x-data="{ activeTab: 'instances' }">
            <!-- Tabs Navigation -->
            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl rounded-xl p-2 border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                <nav class="flex space-x-2" aria-label="Tabs">
                    <button @click="activeTab = 'instances'"
                            :class="{
                                'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400': activeTab === 'instances',
                                'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-white/50 dark:hover:bg-gray-700/50': activeTab !== 'instances'
                            }"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Mes Instances
                    </button>
                    <button @click="activeTab = 'pricing'"
                            :class="{
                                'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400': activeTab === 'pricing',
                                'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-white/50 dark:hover:bg-gray-700/50': activeTab !== 'pricing'
                            }"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Abonnements
                    </button>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="mt-5">
                <!-- Onglet Instances -->
                <div x-show="activeTab === 'instances'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-cloak>
                    <div class="grid lg:grid-cols-12 gap-8">
                        <!-- Colonne Principale -->
                        <div class="lg:col-span-8 space-y-6">
                            @include('livewire.client.sections.instances-list')
                        </div>

                        <!-- Colonne Latérale -->
                        <div class="lg:col-span-4 space-y-6">
                            <!-- Actions rapides -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <h3 class="text-gray-900 dark:text-white">Actions rapides</h3>
                                </div>
                                <div class="p-3">
                                    <div class="space-y-1">
                                        <button class="group w-full flex items-center gap-3 px-3 py-2.5 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <span class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </span>
                                            <span class="font-medium">Entreprises</span>
                                        </button>
                                        <button class="group w-full flex items-center gap-3 px-3 py-2.5 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <span class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                            </span>
                                            <span class="font-medium">Gérer la facturation</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques ou Informations supplémentaires -->
                            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-sm overflow-hidden">
                                <div class="px-6 py-8 relative">
                                    <div class="relative z-10">
                                        <h4 class="text-white font-display font-medium mb-2">Ressources</h4>
                                        <p class="text-primary-100 text-sm">Accédez à notre documentation et nos guides pour tirer le meilleur parti de votre instance.</p>
                                        <a href="#" class="inline-flex items-center gap-2 mt-4 text-white text-sm font-medium hover:underline">
                                            En savoir plus
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="absolute right-0 bottom-0 transform translate-x-1/4 translate-y-1/4">
                                        <svg class="w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 24 24">
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
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-cloak>
                    @include('livewire.client.sections.pricing-plans')
                </div>
            </div>
        </div>
    </div>
</div>
