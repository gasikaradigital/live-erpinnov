<div>
    @push("styles")
        <style>
            .tw-transition-all {
                transition: all 0.5s ease-in-out;
            }
        </style>
    @endpush
    <div class="tw-min-h-screen tw-bg-gray-50 dark:tw-bg-gray-900">
        <!-- Header avec navigation -->
        <div class="tw-bg-white dark:tw-bg-gray-800 tw-border-b dark:tw-border-gray-700 tw-py-4 tw-mt-16">
            <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
                <div class="tw-flex tw-justify-between tw-items-center">
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <a href="{{ route('espaceClient') }}" class="tw-flex tw-items-center tw-text-gray-500 hover:tw-text-gray-700 dark:tw-text-gray-400 dark:hover:tw-text-gray-300">
                            <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="tw-ml-2 tw-text-sm tw-font-medium">Retour au tableau de bord</span>
                        </a>
                        <span class="tw-text-gray-300 dark:tw-text-gray-600">/</span>
                        <span class="tw-text-gray-900 dark:tw-text-gray-100 tw-font-medium">Création d'une nouvelle instance</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="tw-flex-1">
            <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8">
                <div class="tw-grid lg:tw-grid-cols-3 tw-gap-8">
                    <!-- Formulaire principal -->
                    <div class="lg:tw-col-span-2">
                        @if($newInstanceInfo)
                            @include('livewire.client.messages.infocreated')
                        @else
                            <div class="tw-bg-white dark:tw-bg-gray-800 tw-shadow tw-rounded-lg">
                                @if(!$showPlanSelection)
                                    <div class="tw-px-6 tw-py-8 tw-border-b dark:tw-border-gray-700">
                                        <div class="tw-text-center">
                                            <h3 class="tw-font-display tw-text-2xl tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                                Créez votre espace de travail
                                            </h3>
                                            <p class="tw-mt-2 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">
                                                En quelques clics, créez votre environnement professionnel personnalisé
                                            </p>
                                        </div>
                                    </div>

                                    <div class="tw-p-6">
                                        <form wire:submit.prevent="store" class="tw-space-y-6">
                                            <!-- Nom de l'instance -->
                                            <div>
                                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">
                                                    Nom de l'instance
                                                </label>
                                                <div class="tw-mt-2 tw-flex tw-rounded-md tw-shadow-sm">
                                                    <input type="text"
                                                           wire:model="name"
                                                           class="tw-flex-1 tw-block tw-w-full tw-rounded-l-md tw-border-gray-300 dark:tw-border-gray-600 dark:tw-bg-gray-700 focus:tw-border-primary-500 focus:tw-ring-primary-500 sm:tw-text-sm @error('name') tw-border-red-500 @enderror"
                                                           placeholder="votreinstance">
                                                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-rounded-r-md tw-border tw-border-l-0 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-gray-50 dark:tw-bg-gray-600 tw-text-gray-500 dark:tw-text-gray-400 sm:tw-text-sm">
                                                        .erpinnov.com
                                                    </span>
                                                </div>
                                                @error('name')
                                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Sélection de l'entreprise -->
                                            <div>
                                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">
                                                    Votre entreprise
                                                </label>
                                                <select wire:model.live="entreprise_id"
                                                        class="tw-mt-2 tw-block tw-w-full tw-rounded-md tw-border-gray-300 dark:tw-border-gray-600 dark:tw-bg-gray-700 focus:tw-border-primary-500 focus:tw-ring-primary-500 sm:tw-text-sm @error('entreprise_id') tw-border-red-500 @enderror">
                                                    <option value="">Sélectionnez votre entreprise</option>
                                                    @foreach($entreprises as $entreprise)
                                                        <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('entreprise_id')
                                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Affichage du pays -->
                                            @if($entreprise_id)
                                                <div class="tw-bg-gray-50 dark:tw-bg-gray-700 tw-rounded-lg tw-p-3 tw-inline-flex tw-items-center tw-gap-2">
                                                    <img src="{{ asset('client/assets/img/flags/' . ($selectedPays == 'Madagascar' ? '0.png' : '1.png')) }}"
                                                         alt="{{ $selectedPays }}"
                                                         class="tw-w-6 tw-h-6 tw-rounded">
                                                    <span class="tw-font-medium tw-text-gray-900 dark:tw-text-white">{{ $selectedPays }}</span>
                                                </div>
                                            @endif

                                            <!-- Boutons d'action -->
                                            <div class="tw-border-t dark:tw-border-gray-700 tw-pt-6">
                                                <div class="tw-flex tw-justify-between tw-items-center">
                                                    <a href="{{ route('espaceClient') }}"
                                                       class="tw-inline-flex tw-items-center tw-text-sm tw-font-medium tw-text-gray-500 hover:tw-text-gray-700 dark:tw-text-gray-400 dark:hover:tw-text-gray-300">
                                                        <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                                        </svg>
                                                        Annuler
                                                    </a>
                                                    <button type="submit"
                                                            class="tw-btn-primary tw-py-2.5 tw-px-6 tw-rounded-lg tw-text-sm tw-font-semibold focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-primary-500 tw-inline-flex tw-items-center tw-gap-2">
                                                        <span>Créer l'instance</span>
                                                        <svg class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar avec informations -->
                    @include('livewire.client.sections.sidebar')
                </div>
            </div>
        </div>

        <!-- Modal de progression -->
        <div x-data="{
            show: @entangle('isCreating'),
            progress: @entangle('progress'),
            message: @entangle('currentStepMessage')
        }"
             x-show="show"
             x-cloak
             class="tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center"
             x-transition:enter="tw-transition tw-ease-out tw-duration-300"
             x-transition:enter-start="tw-opacity-0"
             x-transition:enter-end="tw-opacity-100"
             x-transition:leave="tw-transition tw-ease-in tw-duration-200"
             x-transition:leave-start="tw-opacity-100"
             x-transition:leave-end="tw-opacity-0">

            <!-- Overlay -->
            <div class="tw-fixed tw-inset-0 tw-bg-black tw-opacity-50"></div>

            <!-- Modal Content -->
            <div class="tw-relative tw-bg-white dark:tw-bg-gray-800 tw-rounded-lg tw-p-6 tw-max-w-md tw-w-full tw-mx-4 tw-shadow-xl">
                <h3 class="tw-text-lg tw-font-semibold tw-text-center tw-text-gray-900 dark:tw-text-white">
                    Création de votre instance
                </h3>

                <!-- Message de l'étape -->
                <p class="tw-mt-2 tw-text-sm tw-text-center tw-text-gray-600 dark:tw-text-gray-400" x-text="message"></p>

                <!-- Barre de progression -->
                <div class="tw-mt-4">
                    <div class="tw-relative tw-pt-1">
                        <div class="tw-flex tw-mb-2 tw-items-center tw-justify-between">
                            <div>
                                <span class="tw-text-xs tw-font-semibold tw-inline-block tw-py-1 tw-px-2 tw-rounded-full tw-text-primary-600 dark:tw-text-primary-400"
                                      x-text="`${progress}%`">
                                </span>
                            </div>
                        </div>
                        <div class="tw-overflow-hidden tw-h-2 tw-mb-4 tw-rounded tw-bg-gray-200 dark:tw-bg-gray-700">
                            <div class="tw-h-2 tw-bg-primary-600 dark:tw-bg-primary-400 tw-transition-all tw-duration-500"
                                 x-bind:style="`width: ${progress}%`">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('progressUpdated', (event) => {
                // Pour déboguer
                console.log('Progress updated:', event);
            });
        });
    </script>
    @endpush
</div>
