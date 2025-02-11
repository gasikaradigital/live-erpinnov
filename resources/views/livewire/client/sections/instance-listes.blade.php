<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl text-gray-900 dark:text-white">Mes instances ({{ $instances->count() }})</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Liste de vos espaces de travail actifs</p>
            </div>
        </div>

        @if($instances->isNotEmpty())
            <div class="overflow-hidden border border-gray-200 bg-white dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instance</th>
                                <th scope="col" class="px-6 py-3 text-center"></th>
                                <th scope="col" class="px-6 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($instances as $instance)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <img class="h-5 w-5 rounded-lg" src="{{ asset('client/assets/img/flags/' . ($instance->pays === 0 ? '0.png' : '1.png')) }}" alt="{{ $instance->getPaysNomAttribute() }}">
                                                <span class="absolute -bottom-2 -right-1 flex h-2 w-2 items-center justify-center">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-50"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $instance->name }}</div>
                                                @if($instance->isTrialPeriod)
                                                    <div class="text-sm text-red-600 dark:text-gray-400">essai</div>
                                                    @if($instance->remainingDays > 0)
                                                        <div class="text-xs text-blue-600">Restant {{ (int)$instance->remainingDays }} jour{{ (int)$instance->remainingDays > 1 ? 's' : '' }}</div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $instance->plan?->name ?? 'Solo' }}</div>
                                            @if($instance->subPlan)
                                                <span class="text-gray-600 dark:text-gray-400">({{ $instance->subPlan->name }})</span>
                                            @elseif($instance->plan?->has_sub_plans)
                                                <small class="text-gray-600 dark:text-gray-400">(Basic)</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Mettre à jour
                                            </a>
                                            <a href="{{ $instance->url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Accéder
                                            </a>
                                            <button wire:click="viewDetail({{ $instance->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
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
                <div class="px-6 py-4">
                    {{ $instances->links() }}
                </div>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white dark:bg-gray-800 p-12">
                <div class="text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-700">
                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Aucune instance</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Commencez par créer votre première instance</p>
                    <button class="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-medium text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Créer une instance
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
