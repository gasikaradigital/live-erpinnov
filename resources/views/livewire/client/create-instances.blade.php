<!-- resources/views/instance/create.blade.php -->
<div class="min-h-screen bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <br><br><br>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Form Section -->
            <div class="lg:col-span-2">
                @if($newInstanceInfo)
                    @include('livewire.client.messages.infocreated')
                @else
                    <div class="relative overflow-hidden bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-transparent dark:from-primary-400/10"></div>
                        <div class="absolute right-0 top-0 h-px w-32 bg-gradient-to-l from-primary-500/50 to-transparent"></div>
                        <div class="absolute left-0 bottom-0 h-px w-32 bg-gradient-to-r from-primary-500/50 to-transparent"></div>

                        @unless($showPlanSelection)
                            <div class="relative">
                                <!-- Enhanced Header -->
                                <div class="px-8 py-8 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <div class="flex items-start gap-6">
                                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-0.5">
                                            <div class="h-full w-full rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-primary-400 bg-clip-text text-transparent">
                                                Créez votre espace de travail
                                            </h2>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                Configurez votre environnement professionnel en quelques étapes simples
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Form -->
                                <div class="p-8 space-y-8">
                                    <form wire:submit.prevent="store" class="space-y-8">
                                    <!-- Modification du champ nom -->
                                    <div class="space-y-4">
                                        <label class="block text-base font-medium text-gray-900 dark:text-white">
                                            Nom de votre instance <span class="text-red-500">*</span>
                                        </label>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Ce nom sera utilisé pour accéder à votre espace de travail
                                        </p>
                                        <div class="group">
                                            <div class="flex overflow-hidden rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 transition duration-300 focus-within:ring-2 focus-within:ring-primary-500 focus-within:shadow-lg">
                                                <div class="flex-1 flex items-center">
                                                    <div class="flex items-center px-4 text-gray-500">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                        </svg>
                                                    </div>
                                                    <input type="text"
                                                        wire:model="name"
                                                        class="flex-1 px-4 py-3 border-0 bg-transparent text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 sm:text-sm"
                                                        placeholder="votreinstance">
                                                </div>
                                                <div class="flex items-center px-4 bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-sm border-l border-gray-200 dark:border-gray-700">
                                                    .erpinnov.com
                                                </div>
                                            </div>
                                            @error('name')
                                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        </div>
                                       <!-- Select avec groupe et icône -->
                                        <div class="space-y-4">
                                            <label class="block text-base font-medium text-gray-900 dark:text-white">
                                                Entreprise associée <span class="text-red-500">*</span>
                                            </label>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                Cette instance sera liée à l'entreprise sélectionnée
                                            </p>
                                            <div class="group">
                                                <div class="relative flex rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 focus-within:ring-2 focus-within:ring-primary-500">
                                                    <div class="flex items-center pl-4">
                                                        <!-- Spinner pendant le chargement -->
                                                        <div wire:loading wire:target="entreprise_id">
                                                            <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                        <!-- Icône par défaut -->
                                                        <div wire:loading.remove wire:target="entreprise_id">
                                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="relative flex-1">
                                                        <select wire:model.live="entreprise_id"
                                                                class="block w-full rounded-r-xl border-0 py-3 pl-3 pr-10 bg-transparent text-gray-900 dark:text-white focus:ring-0 sm:text-sm appearance-none">
                                                            <option value="">Sélectionnez votre entreprise</option>
                                                            @foreach($entreprises as $entreprise)
                                                                <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('entreprise_id')
                                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Affichage du pays -->
                                        @if($entreprise_id)
                                            <div wire:loading.class="opacity-50" wire:target="entreprise_id" class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-transparent dark:from-gray-700/50 dark:to-transparent rounded-xl border border-gray-200/50 dark:border-gray-600/50">
                                                <div class="relative">
                                                    <div class="h-12 w-12 rounded-xl overflow-hidden ring-2 ring-white dark:ring-gray-700 shadow-lg">
                                                        <img src="{{ asset('client/assets/img/flags/' . ($selectedPays == 'Madagascar' ? '0.png' : '1.png')) }}"
                                                            alt="{{ $selectedPays }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></div>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPays }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Localisation de l'entreprise</span>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Enhanced Actions -->
                                        <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200/50 dark:border-gray-700/50">
                                            <a href="{{ route('instance.create')}}" wire:navigate
                                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Actualiser
                                            </a>
                                            <x-button-create/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="p-8">
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
