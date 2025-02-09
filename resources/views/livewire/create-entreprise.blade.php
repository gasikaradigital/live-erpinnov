<div class="min-h-screen bg-gray-50">
    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Added Companies Summary --}}
        @if($entreprises->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Entreprises ajoutées ({{ $entreprises->count() }})</h2>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
                @foreach($entreprises as $entreprise)
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold">
                            {{ substr($entreprise->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ $entreprise->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $entreprise->ville }}, {{ $entreprise->pays }}</p>
                        </div>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mt-12">
            <div class="px-6 py-8 border-b border-gray-200 bg-gradient-to-r from-primary-500 to-primary-600">
                <h2 class="text-2xl font-bold text-white">Ajouter une nouvelle entreprise</h2>
                <p class="mt-2 text-primary-100">Remplissez les informations ci-dessous pour ajouter une nouvelle entreprise.</p>
            </div>

            <form wire:submit.prevent="store" class="p-6 space-y-6">
                {{-- Form Content --}}
                <div class="space-y-6">
                    {{-- Nom de l'organisation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de l'organisation</label>
                        <div class="mt-1 relative rounded-lg">
                            <input type="text" wire:model="name"
                                class="block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 pl-4 pr-10"
                                placeholder="Ex: Space X">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIF/SIREN et Téléphone --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIF ou SIREN</label>
                            <div class="mt-1">
                                <input type="text" wire:model="nif"
                                    class="block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Numéro fiscal">
                            </div>
                            @error('nif')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <div class="mt-1">
                                <input type="tel" wire:model="phone"
                                    class="block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="+261 xx xx xxx xx">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nombre d'employés --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre d'employés</label>
                        <select wire:model="employees_count"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Sélectionnez</option>
                            <option value="1-10">0-5 employés</option>
                            <option value="1-10">1-10 employés</option>
                            <option value="11-50">11-50 employés</option>
                            <option value="51-200">51-200 employés</option>
                            <option value="201+">201+ employés</option>
                        </select>
                        @error('employees_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adresse complète --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adresse complète</label>
                        <textarea wire:model="adresse" rows="3"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Adresse de l'entreprise"></textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ville et Pays --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" wire:model="ville"
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Ex: Antananarivo">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pays</label>
                            <x-country-select :selected="$pays" class="mt-1" />
                            @error('pays')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <span>Ajouter l'entreprise</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>

                    @if($showTerminerButton)
                    <button type="button" wire:click="terminer"
                        class="inline-flex items-center px-6 py-3 bg-success-600 text-white rounded-lg hover:bg-success-700 focus:ring-2 focus:ring-offset-2 focus:ring-success-500 transition-colors">
                        Passer à l'étape suivante
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
