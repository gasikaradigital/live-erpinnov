<!-- sections/instances-list.blade.php -->
<div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-overflow-hidden">
    <div class="tw-p-6">
        <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
            <div>
                <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Mes instances</h2>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Liste de vos espaces de travail actifs</p>
            </div>
        </div>

        @if($instances->isNotEmpty())
            <div class="tw-overflow-x-auto">
                <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Instance</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Identifiant</th>
                            <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Statut</th>
                            <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-gray-200">
                        @foreach($instances as $instance)
                            <tr class="hover:tw-bg-gray-50">
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <img class="tw-h-10 tw-w-10 tw-rounded-lg tw-object-cover" 
                                             src="{{ asset('assets/img/flags/' . ($instance->pays === 0 ? '0.png' : '1.png')) }}"
                                             alt="{{ $instance->getPaysNomAttribute() }}">
                                        <div>
                                            <div class="tw-font-medium tw-text-gray-900">{{ $instance->getPaysNomAttribute() }}</div>
                                            <div class="tw-text-sm tw-text-gray-500">Créé le {{ Carbon\Carbon::parse($instance->created_at)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-text-sm tw-text-gray-900">{{ $instance->dolibarr_username }}</div>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <span class="tw-px-2.5 tw-py-1 tw-text-xs tw-font-medium tw-rounded-full tw-bg-green-100 tw-text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-right">
                                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-3">
                                        <a href="{{ $instance->url }}" 
                                           target="_blank"
                                           class="tw-text-sm tw-text-indigo-600 hover:tw-text-indigo-900">
                                            Accéder
                                        </a>
                                        <button wire:click="viewDetail({{ $instance->id }})"
                                                class="tw-text-sm tw-text-gray-500 hover:tw-text-gray-700">
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
                <div class="tw-inline-flex tw-items-center tw-justify-center tw-w-16 tw-h-16 tw-rounded-full tw-bg-gray-100">
                    <svg class="tw-w-8 tw-h-8 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="tw-mt-4 tw-text-lg tw-font-medium tw-text-gray-900">Aucune instance</h3>
                <p class="tw-mt-2 tw-text-sm tw-text-gray-500">Créez votre première instance pour commencer</p>
            </div>
        @endif
    </div>
</div>