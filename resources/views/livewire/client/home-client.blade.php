{{-- Modern Dashboard View with Pricing Plan --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 pt-16 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Layout principal --}}
        <div class="grid lg:grid-cols-12 gap-8">
            {{-- Colonne principale --}}
            <div class="lg:col-span-12 space-y-12">
                {{-- Liste des instances --}}
                @include('livewire.client.sections.instance-listes')

                {{-- Composant Plan tarifaire --}}
                <div class="mt-8" id="plan">
                    <livewire:payment.pricing-plan />
                </div>
            </div>

            {{-- Colonne latérale --}}
            {{-- <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions rapides</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-2">
                            <a href="#" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <span class="flex-shrink-0 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Gérer les entreprises</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Configurez vos informations d'entreprise</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <a href="#" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <span class="flex-shrink-0 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Facturation</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Gérez vos abonnements et paiements</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-sm overflow-hidden relative">
                    <div class="px-6 py-8">
                        <div class="relative z-10">
                            <h4 class="text-xl font-semibold text-white mb-2">Centre de ressources</h4>
                            <p class="text-primary-100">Accédez à notre documentation complète et nos guides détaillés.</p>
                            <div class="mt-6 space-y-3">
                                <a href="#" class="flex items-center text-white hover:bg-white/10 rounded-lg p-3 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Documentation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</div>
