<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
    <div class="p-6">
        <!-- Header avec recherche et filtres -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    Mes instances
                    <span class="px-2.5 py-0.5 rounded-full text-sm bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                        {{ $instances->count() }}
                    </span>
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Liste de vos espaces de travail actifs</p>
            </div>

            <!-- Bouton créer une nouvelle instance -->
            <a href="#plan" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition duration-150 ease-in-out shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvelle instance
            </a>
        </div>

        @if($instances->isNotEmpty())
            <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instance</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plan</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($instances as $instance)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-150">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <div class="relative h-12 w-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                                    <img
                                                        class="h-8 w-8 object-cover"
                                                        src="{{ asset('client/assets/img/flags/' . ($instance->pays === 0 ? '0.png' : '1.png')) }}"
                                                        alt="{{ $instance->getPaysNomAttribute() }}"
                                                    >
                                                </div>
                                                <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-50"></span>
                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-base font-semibold text-gray-900 dark:text-white">
                                                    {{ $instance->name }}
                                                </div>
                                                @if($instance->isTrialPeriod)
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                            Version d'essai
                                                        </span>
                                                        @if($instance->remainingDays > 0)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                {{ (int)$instance->remainingDays }} jour{{ (int)$instance->remainingDays > 1 ? 's' : '' }} restant{{ (int)$instance->remainingDays > 1 ? 's' : '' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                {{ $instance->plan?->name ?? 'Solo' }}
                                            </span>
                                            @if($instance->subPlan)
                                                <span class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $instance->subPlan->name }}
                                                </span>
                                            @elseif($instance->plan?->has_sub_plans)
                                                <span class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Basic
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                <span class="hidden sm:inline">Mettre à jour</span>
                                            </button>

                                            <a
                                                href="{{ $instance->url }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                <span class="hidden sm:inline">Accéder</span>
                                            </a>

                                            <button
                                                wire:click="viewDetail({{ $instance->id }})"
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="hidden sm:inline">Détails</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $instances->links() }}
                </div>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/50">
                        <svg class="h-10 w-10 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Aucune instance</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Commencez par créer votre première instance</p>
                    <button class="mt-6 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition duration-150 ease-in-out shadow-sm hover:shadow-md active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Créer une instance
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
