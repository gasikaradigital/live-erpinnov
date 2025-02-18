<!-- resources/views/instance/create.blade.php -->
<div class="min-h-screen bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Form Section -->
            <div class="lg:col-span-2">
                @if($newInstanceInfo)
                    @include('livewire.client.messages.infocreated')
                @else
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-transparent dark:from-primary-400/10"></div>

                        @unless($showPlanSelection)
                            <!-- Section du formulaire principal -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                                <!-- En-tête avec icône -->
                                <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="h-12 w-12 rounded-xl bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                Créez votre espace de travail
                                            </h2>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Configurez votre environnement professionnel
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Corps du formulaire -->
                                <div class="p-6 space-y-6">
                                    <!-- Champ Nom de l'instance -->
                                    <div class="space-y-2">
                                        <label class="flex items-baseline justify-between">
                                            <div class="flex gap-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">Nom de votre instance</span>
                                                <span class="text-red-500">*</span>
                                            </div>
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Ce nom sera utilisé pour accéder à votre espace
                                        </p>

                                        <div class="relative mt-1">
                                            <div class="group">
                                                <div class="flex overflow-hidden rounded-lg ring-1 ring-gray-200 dark:ring-gray-700 focus-within:ring-2 focus-within:ring-primary-500 transition duration-150">
                                                    <div class="flex items-center pl-3 text-gray-500">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                        </svg>
                                                    </div>
                                                    <input type="text"
                                                        wire:model="name"
                                                        class="flex-1 px-3 py-2.5 bg-transparent text-sm text-gray-900 dark:text-white placeholder-gray-400 border-0 focus:ring-0"
                                                        placeholder="votreinstance">
                                                    <div class="flex items-center px-3 bg-gray-50 dark:bg-gray-700/50 text-sm text-gray-500 dark:text-gray-400 border-l border-gray-200 dark:border-gray-700">
                                                        .erpinnov.com
                                                    </div>
                                                </div>
                                            </div>
                                            @error('name')
                                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Champ Entreprise associée -->
                                    <div class="space-y-2">
                                        <label class="flex items-baseline justify-between">
                                            <div class="flex gap-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">Entreprise associée</span>
                                                <span class="text-red-500">*</span>
                                            </div>
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Cette instance sera liée à l'entreprise sélectionnée
                                        </p>
                                        <div class="relative mt-1">
                                            <div class="flex overflow-hidden rounded-lg ring-1 ring-gray-200 dark:ring-gray-700 focus-within:ring-2 focus-within:ring-primary-500 transition duration-150">
                                                <div class="flex items-center pl-3 text-gray-500">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                                <select wire:model.live="entreprise_id"
                                                        class="flex-1 px-3 py-2.5 bg-transparent text-sm text-gray-900 dark:text-white border-0 focus:ring-0 appearance-none">
                                                    <option value="">Sélectionnez votre entreprise</option>
                                                    @foreach($entreprises as $entreprise)
                                                        <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('entreprise_id')
                                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                            @enderror
                                            <div class="flex items-center pl-4 mt-3">
                                                <!-- Spinner pendant le chargement -->
                                                <div wire:loading wire:target="entreprise_id">
                                                    <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Affichage du pays -->
                                        @if($entreprise_id)
                                            <div wire:loading.class="opacity-50" wire:target="entreprise_id" class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-transparent dark:from-gray-700/50 dark:to-transparent rounded-xl border border-gray-200/50 dark:border-gray-600/50">
                                                <div class="relative">
                                                    <div class="h-8 w-8 overflow-hidden ring-2 ring-white dark:ring-gray-700">
                                                        <img src="{{ asset('client/assets/img/flags/' . ($selectedPays == 'Madagascar' ? '0.png' : '1.png')) }}"
                                                            alt="{{ $selectedPays }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPays }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Localisation de l'entreprise</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200/50 dark:border-gray-700/50">
                                        <button type="button"
                                                wire:click="$refresh"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Actualiser
                                        </button>
                                        <x-button-create/>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-6">
                                @include('livewire.payment.free_expired')
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            @include('livewire.client.sections.sidebar')
        </div>
    </div>
</div>
