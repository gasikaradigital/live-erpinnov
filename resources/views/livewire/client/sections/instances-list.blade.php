<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl text-gray-900 dark:text-white">Mes instances</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Liste de vos espaces de travail actifs</p>
            </div>

            <!-- Optionnel: Bouton d'ajout d'instance -->
            <a href="{{ route('instance.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-200 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle instance
            </a>
        </div>

        @if($instances->isNotEmpty())
            <div class="overflow-x-auto rounded-xl">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Instance</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Identifiant</span>
                            </th>
                            <th class="px-6 py-3 text-center">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Statut</span>
                            </th>
                            <th class="px-6 py-3 text-right">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($instances as $instance)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-gray-100 dark:ring-gray-800 group-hover:ring-primary-100 dark:group-hover:ring-primary-900/30 transition-all"
                                                 src="{{ asset('client/assets/img/flags/' . ($instance->pays === 0 ? '0.png' : '1.png')) }}"
                                                 alt="{{ $instance->getPaysNomAttribute() }}">
                                            <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
                                                <span class="flex h-3 w-3">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $instance->getPaysNomAttribute() }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Créé le {{ Carbon\Carbon::parse($instance->created_at)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-sm text-gray-900 dark:text-white">{{ $instance->dolibarr_username }}</span>
                                        <button class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Copier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-full bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400">
                                            <svg class="w-2 h-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Active
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ $instance->url }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Accéder
                                        </a>
                                        <button wire:click="viewDetail({{ $instance->id }})"
                                                class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Détails
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800/50">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-display font-medium text-gray-900 dark:text-white">Aucune instance</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Créez votre première instance pour commencer</p>
            </div>
        @endif
    </div>
</div>
