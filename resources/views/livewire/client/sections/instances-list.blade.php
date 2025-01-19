<div class="tw-bg-white/80 dark:tw-bg-gray-800/80 tw-backdrop-blur-xl tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-200/50 dark:tw-border-gray-700/50 tw-overflow-hidden">
    <div class="tw-p-6">
        <!-- Header -->
        <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
            <div>
                <h2 class="tw-text-xl tw-text-gray-900 dark:tw-text-white">Mes instances</h2>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Liste de vos espaces de travail actifs</p>
            </div>

            <!-- Optionnel: Bouton d'ajout d'instance -->
            <a href="{{ route('instance.create') }}" class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-primary-600 dark:tw-text-primary-400 tw-bg-primary-200 dark:tw-bg-primary-900/20 tw-rounded-lg hover:tw-bg-primary-100 dark:hover:tw-bg-primary-900/30 tw-transition-colors">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle instance
            </a>
        </div>

        @if($instances->isNotEmpty())
            <div class="tw-overflow-x-auto tw-rounded-xl">
                <table class="tw-min-w-full">
                    <thead>
                        <tr class="tw-bg-gray-50/50 dark:tw-bg-gray-900/50">
                            <th class="tw-px-6 tw-py-3 tw-text-left">
                                <span class="tw-text-xs tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-uppercase">Instance</span>
                            </th>
                            <th class="tw-px-6 tw-py-3 tw-text-left">
                                <span class="tw-text-xs tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-uppercase">Identifiant</span>
                            </th>
                            <th class="tw-px-6 tw-py-3 tw-text-center">
                                <span class="tw-text-xs tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-uppercase">Statut</span>
                            </th>
                            <th class="tw-px-6 tw-py-3 tw-text-right">
                                <span class="tw-text-xs tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-uppercase">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-gray-100 dark:tw-divide-gray-800">
                        @foreach($instances as $instance)
                            <tr class="group hover:tw-bg-gray-50/50 dark:hover:tw-bg-gray-900/50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <div class="tw-relative">
                                            <img class="tw-h-10 tw-w-10 tw-rounded-lg tw-object-cover tw-ring-2 tw-ring-gray-100 dark:tw-ring-gray-800 group-hover:tw-ring-primary-100 dark:group-hover:tw-ring-primary-900/30 tw-transition-all"
                                                 src="{{ asset('client/assets/img/flags/' . ($instance->pays === 0 ? '0.png' : '1.png')) }}"
                                                 alt="{{ $instance->getPaysNomAttribute() }}">
                                            <div class="tw-absolute tw-bottom-0 tw-right-0 tw-transform tw-translate-x-1/4 tw-translate-y-1/4">
                                                <span class="tw-flex tw-h-3 tw-w-3">
                                                    <span class="tw-animate-ping tw-absolute tw-inline-flex tw-h-full tw-w-full tw-rounded-full tw-bg-green-400 tw-opacity-75"></span>
                                                    <span class="tw-relative tw-inline-flex tw-rounded-full tw-h-3 tw-w-3 tw-bg-green-500"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="tw-font-medium tw-text-gray-900 dark:tw-text-white">{{ $instance->getPaysNomAttribute() }}</div>
                                            <div class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Créé le {{ Carbon\Carbon::parse($instance->created_at)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <span class="tw-font-mono tw-text-sm tw-text-gray-900 dark:tw-text-white">{{ $instance->dolibarr_username }}</span>
                                        <button class="tw-p-1 tw-rounded-md hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700 tw-text-gray-400 hover:tw-text-gray-600 dark:hover:tw-text-gray-300 tw-transition-colors" title="Copier">
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-justify-center">
                                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1.5 tw-text-xs tw-font-medium tw-rounded-full tw-bg-green-50 dark:tw-bg-green-900/20 tw-text-green-700 dark:tw-text-green-400">
                                            <svg class="tw-w-2 tw-h-2 tw-fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Active
                                        </span>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-3">
                                        <a href="{{ $instance->url }}"
                                           target="_blank"
                                           class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-medium tw-text-primary-600 dark:tw-text-primary-400 hover:tw-text-primary-700 dark:hover:tw-text-primary-300 tw-transition-colors">
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Accéder
                                        </a>
                                        <button wire:click="viewDetail({{ $instance->id }})"
                                                class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 hover:tw-text-gray-700 dark:hover:tw-text-gray-200 tw-transition-colors">
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="tw-text-center tw-py-12">
                <div class="tw-inline-flex tw-items-center tw-justify-center tw-w-20 tw-h-20 tw-rounded-full tw-bg-gray-50 dark:tw-bg-gray-800/50">
                    <svg class="tw-w-10 tw-h-10 tw-text-gray-400 dark:tw-text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="tw-mt-4 tw-text-lg tw-font-display tw-font-medium tw-text-gray-900 dark:tw-text-white">Aucune instance</h3>
                <p class="tw-mt-2 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Créez votre première instance pour commencer</p>
                {{-- <button class="tw-mt-4 tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-white tw-bg-primary-600 hover:tw-bg-primary-700 tw-rounded-lg tw-transition-colors">
                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer une instance
                </button> --}}
            </div>
        @endif
    </div>
</div>
