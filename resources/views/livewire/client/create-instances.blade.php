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

                                            <!-- Barre de progression -->
                                            <div class="tw-mt-6">
                                                @if($isCreating)
                                                    <div class="tw-space-y-4">
                                                        <div class="tw-flex tw-justify-between tw-items-center">
                                                            <span class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">
                                                                {{ $steps[$currentStep] }}
                                                            </span>
                                                            <span class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">
                                                                {{ $progress }}%
                                                            </span>
                                                        </div>
                                                        <div class="tw-w-full tw-bg-gray-200 dark:tw-bg-gray-700 tw-rounded-full tw-h-2.5">
                                                            <div class="tw-bg-primary-600 tw-h-2.5 tw-rounded-full tw-transition-all tw-duration-500"
                                                                 style="width: {{ $progress }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

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
                                                            class="tw-btn-primary tw-py-2.5 tw-px-6 tw-rounded-lg tw-text-sm tw-font-semibold focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-primary-500 tw-inline-flex tw-items-center tw-gap-2 disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                                                            wire:loading.attr="disabled"
                                                            wire:target="store">
                                                        <span>{{ $isCreating ? 'Création en cours...' : 'Créer l\'instance' }}</span>
                                                        @if(!$isCreating)
                                                            <svg class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                            </svg>
                                                        @else
                                                            <svg class="tw-w-4 tw-h-4 tw-animate-spin" fill="none" viewBox="0 0 24 24">
                                                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <!-- Message pour mise à niveau -->
                                    <div class="tw-p-8 tw-text-center">
                                        <div class="tw-mx-auto tw-flex tw-items-center tw-justify-center tw-h-12 tw-w-12 tw-rounded-full tw-bg-red-100 dark:tw-bg-red-900 tw-mb-4">
                                            <svg class="tw-h-6 tw-w-6 tw-text-red-600 dark:tw-text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <h3 class="tw-text-lg tw-font-medium tw-text-gray-900 dark:tw-text-white">Limite d'instances atteinte</h3>
                                        <p class="tw-mt-2 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">
                                            Passez à un forfait supérieur pour créer plus d'instances
                                        </p>
                                        <div class="tw-mt-6">
                                            <button class="tw-btn-primary tw-py-2 tw-px-4 tw-rounded-lg tw-text-sm tw-font-semibold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#pricingModal">
                                                <span class="tw-flex tw-items-center tw-gap-2">
                                                    <svg class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    <span>Voir les forfaits</span>
                                                </span>
                                            </button>
                                        </div>
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

    <!-- Script pour la progression -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('progressUpdated', (data) => {
                console.log('Progress:', data.progress, 'Step:', data.step);
            });
        });
    </script>
</div>
