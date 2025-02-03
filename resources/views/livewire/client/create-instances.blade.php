<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 pb-20 mt-16">
        <x-header-nav title="Création d'une nouvelle instance"/>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Formulaire principal -->
                <div class="lg:col-span-2">
                    @if($newInstanceInfo)
                        @include('livewire.client.messages.infocreated')
                    @else
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                            @if(!$showPlanSelection)
                                <!-- En-tête -->
                                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <h3 class="font-display text-2xl font-semibold bg-gradient-to-r from-primary-600 to-primary-400 bg-clip-text text-transparent">
                                        Créez votre espace de travail
                                    </h3>
                                    <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                                        Configurez votre environnement professionnel en quelques étapes simples
                                    </p>
                                </div>

                                <div class="p-8">
                                    <form wire:submit.prevent="store" class="space-y-8">
                                        <!-- Nom de l'instance -->
                                        <div class="space-y-4">
                                            <label class="block">
                                                <span class="text-base font-medium text-gray-700 dark:text-gray-300">
                                                    Comment souhaitez-vous nommer votre instance ?
                                                </span>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Ce nom sera utilisé pour accéder à votre espace de travail
                                                </p>
                                            </label>
                                            <div class="relative group">
                                                <div class="flex rounded-xl shadow-sm">
                                                    <input type="text"
                                                           wire:model="name"
                                                           class="flex-1 block w-full rounded-l-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 text-base @error('name') border-red-500 @enderror"
                                                           placeholder="votreinstance">
                                                    <span class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-base">
                                                        .erpinnov.com
                                                    </span>
                                                </div>
                                                @error('name')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Sélection de l'entreprise -->
                                        <div class="space-y-4">
                                            <label class="block">
                                                <span class="text-base font-medium text-gray-700 dark:text-gray-300">
                                                    Sélectionnez votre entreprise
                                                </span>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Cette instance sera liée à l'entreprise sélectionnée
                                                </p>
                                            </label>
                                            <select wire:model.live="entreprise_id"
                                                    class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 text-base @error('entreprise_id') border-red-500 @enderror">
                                                <option value="">Sélectionnez votre entreprise</option>
                                                @foreach($entreprises as $entreprise)
                                                    <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('entreprise_id')
                                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Affichage du pays -->
                                        @if($entreprise_id)
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 inline-flex items-center gap-2">
                                                <img src="{{ asset('client/assets/img/flags/' . ($selectedPays == 'Madagascar' ? '0.png' : '1.png')) }}"
                                                    alt="{{ $selectedPays }}"
                                                    class="w-6 h-6 rounded-full">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $selectedPays }}</span>
                                            </div>
                                        @endif

                                        <!-- Boutons d'action -->
                                        <div class="border-t border-gray-200/50 dark:border-gray-700/50 pt-6">
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('instance.create')}}" wire:navigate
                                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Actualiser
                                                </a>
                                               <x-button-create/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="p-8 text-center">
                                    @include('livewire.payment.free_expired')
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
