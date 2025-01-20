<div class="flex h-full pt-16">
    {{-- Colonne gauche --}}
    <div class="w-1/3 bg-primary-600">
        <div class="p-8">
            {{-- Message de bienvenue et étapes --}}
            <div class="mb-8">
                <p class="text-base text-primary-100">
                    Commencez votre voyage avec ERP INNOV en configurant les informations essentielles de votre entreprise
                </p>
            </div>

            {{-- Étapes --}}
            <div class="space-y-4">
                <div class="flex items-center text-white bg-white/10 rounded-lg p-3">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="text-sm">1</span>
                    </div>
                    <span class="ml-3 text-sm">Informations générales</span>
                </div>
                <div class="flex items-center text-white/50 p-3">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                        <span class="text-sm">2</span>
                    </div>
                    <span class="ml-3 text-sm">Configuration des services</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Colonne droite (Formulaire) --}}
    <div class="flex-1 bg-gray-50">
        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                {{-- Message d'information --}}
                @if($entreprises->isNotEmpty())
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg flex items-center mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        Vous avez déjà ajouté <strong>{{ $entreprises->count() }}</strong> entreprise(s).
                        @if($entreprises->count() == 1)
                            Vous pouvez en ajouter d'autres ou terminer.
                        @else
                            Vous pouvez continuer à en ajouter ou terminer.
                        @endif
                    </div>
                </div>
                @endif

                {{-- Formulaire avec card --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <form wire:submit.prevent="store" class="space-y-6">
                        {{-- Nom entreprise --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                            <input type="text" wire:model="name"
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Ex: ERP INNOV">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIF et Nombre d'employés --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    NIF <span class="text-gray-400">(facultatif)</span>
                                </label>
                                <input type="text" wire:model="nif"
                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Numéro fiscal">
                                @error('nif')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre d'employés</label>
                                <select wire:model="employees_count"
                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Sélectionnez</option>
                                    <option value="1-10">1-10 employés</option>
                                    <option value="11-50">11-50 employés</option>
                                    <option value="51-200">51-200 employés</option>
                                    <option value="201+">201+ employés</option>
                                </select>
                                @error('employees_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="tel" wire:model="phone"
                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="+261 xx xx xxx xx">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Adresse --}}
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

                        {{-- Boutons d'action --}}
                        <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <span>Ajouter l'entreprise</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/>
                                </svg>
                            </button>

                            @if($showTerminerButton)
                            <button type="button" wire:click="terminer"
                                class="inline-flex items-center px-6 py-2.5 bg-success-600 text-white rounded-lg hover:bg-success-700 focus:ring-2 focus:ring-offset-2 focus:ring-success-500">
                                <span>Terminer</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
