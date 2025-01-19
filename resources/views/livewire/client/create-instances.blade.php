<!-- create-instances.blade.php -->
<div>
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
        <div class="tw-flex-1 tw-bg-gray-50 dark:tw-bg-gray-900">
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
                                            <h3 class="tw-font-display tw-text-2xl tw-font-semibold tw-text-gray-900 dark:tw-text-white">Créez votre espace de travail</h3>
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

                                            <!-- Sélection entreprise -->
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

                                            <!-- Barre de progression -->
                                            @if($isCreating)
                                                <div class="tw-space-y-3">
                                                    <div class="tw-flex tw-justify-between tw-items-center">
                                                        <span class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">
                                                            Création en cours...
                                                        </span>
                                                        <span class="tw-text-sm tw-text-gray-600 dark:tw-text-gray-400">
                                                            {{ $progress }}%
                                                        </span>
                                                    </div>
                                                    <div class="tw-relative tw-w-full">
                                                        <div class="tw-overflow-hidden tw-h-2 tw-bg-gray-200 dark:tw-bg-gray-700 tw-rounded-full">
                                                            <div class="tw-transition-all tw-duration-500 tw-ease-in-out tw-h-full tw-bg-primary-500 tw-rounded-full"
                                                                 style="width: {{ $progress }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tw-text-center tw-text-sm tw-text-gray-600 dark:tw-text-gray-400">
                                                        @switch($currentStep)
                                                            @case('validation')
                                                                Validation des données...
                                                                @break
                                                            @case('init')
                                                                Initialisation...
                                                                @break
                                                            @case('provision')
                                                                Configuration du serveur...
                                                                @break
                                                            @case('database')
                                                                Création de la base de données...
                                                                @break
                                                            @case('users')
                                                                Configuration des utilisateurs...
                                                                @break
                                                            @case('complete')
                                                                Finalisation...
                                                                @break
                                                            @default
                                                                Préparation...
                                                        @endswitch
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Message d'erreur -->
                                            @if($errorMessage)
                                                <div class="tw-rounded-md tw-bg-red-50 dark:tw-bg-red-900/50 tw-p-4">
                                                    <div class="tw-flex">
                                                        <div class="tw-flex-shrink-0">
                                                            <svg class="tw-h-5 tw-w-5 tw-text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="tw-ml-3">
                                                            <h3 class="tw-text-sm tw-font-medium tw-text-red-800 dark:tw-text-red-200">
                                                                Une erreur est survenue
                                                            </h3>
                                                            <div class="tw-mt-2 tw-text-sm tw-text-red-700 dark:tw-text-red-300">
                                                                {{ $errorMessage }}
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                            class="tw-btn-primary tw-py-2.5 tw-px-6 tw-rounded-lg tw-text-sm tw-font-semibold focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-primary-500 tw-inline-flex tw-items-center tw-gap-2"
                                                            wire:loading.attr="disabled"
                                                            wire:loading.class="tw-opacity-50 tw-cursor-not-allowed"
                                                            @if($isCreating) disabled @endif>
                                                        <span>Créer l'instance</span>
                                                        <svg class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <!-- Section plan selection [inchangée] -->
                                    <div class="tw-p-8 tw-text-center">
                                        @include('livewire.client.sections.free_expired')
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
    </div>

    @script
    <script>
        document.addEventListener('livewire:init', () => {
            let processingSteps = false;

            Livewire.on('progressUpdate', async (step) => {
                if (processingSteps) return;
                processingSteps = true;

                await new Promise(resolve => setTimeout(resolve, 500));
                processingSteps = false;
            });
        });
    </script>
    @endscript
</div>
