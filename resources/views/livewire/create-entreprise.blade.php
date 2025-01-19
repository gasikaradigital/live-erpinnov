<div class="tw-flex tw-h-full tw-pt-16">
    {{-- Colonne gauche --}}
    <div class="tw-w-1/3 tw-bg-primary-600">
        <div class="tw-p-8">
            {{-- Message de bienvenue et étapes --}}
            <div class="tw-mb-8">
                <p class="tw-text-base tw-text-primary-100">
                    Commencez votre voyage avec ERP INNOV en configurant les informations essentielles de votre entreprise
                </p>
            </div>

            {{-- Étapes --}}
            <div class="tw-space-y-4">
                <div class="tw-flex tw-items-center tw-text-white tw-bg-white/10 tw-rounded-lg tw-p-3">
                    <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                        <span class="tw-text-sm">1</span>
                    </div>
                    <span class="tw-ml-3 tw-text-sm">Informations générales</span>
                </div>
                <div class="tw-flex tw-items-center tw-text-white/50 tw-p-3">
                    <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-white/10 tw-flex tw-items-center tw-justify-center">
                        <span class="tw-text-sm">2</span>
                    </div>
                    <span class="tw-ml-3 tw-text-sm">Configuration des services</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Colonne droite (Formulaire) --}}
    <div class="tw-flex-1 tw-bg-gray-50">
        <div class="tw-p-8">
            <div class="tw-max-w-4xl tw-mx-auto">
                {{-- Message d'information --}}
                @if($entreprises->isNotEmpty())
                <div class="tw-bg-green-50 tw-border tw-border-green-200 tw-text-green-800 tw-p-4 tw-rounded-lg tw-flex tw-items-center tw-mb-6">
                    <svg class="tw-w-5 tw-h-5 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-p-6">
                    <form wire:submit.prevent="store" class="tw-space-y-6">
                        {{-- Nom entreprise --}}
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Nom de l'entreprise</label>
                            <input type="text" wire:model="name"
                                class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500"
                                placeholder="Ex: ERP INNOV">
                            @error('name')
                                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIF et Nombre d'employés --}}
                        <div class="tw-grid tw-grid-cols-2 tw-gap-6">
                            <div>
                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                    NIF <span class="tw-text-gray-400">(facultatif)</span>
                                </label>
                                <input type="text" wire:model="nif"
                                    class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500"
                                    placeholder="Numéro fiscal">
                                @error('nif')
                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Nombre d'employés</label>
                                <select wire:model="employees_count"
                                    class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500">
                                    <option value="">Sélectionnez</option>
                                    <option value="1-10">1-10 employés</option>
                                    <option value="11-50">11-50 employés</option>
                                    <option value="51-200">51-200 employés</option>
                                    <option value="201+">201+ employés</option>
                                </select>
                                @error('employees_count')
                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Téléphone</label>
                            <input type="tel" wire:model="phone"
                                class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500"
                                placeholder="+261 xx xx xxx xx">
                            @error('phone')
                                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Adresse --}}
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Adresse complète</label>
                            <textarea wire:model="adresse" rows="3"
                                class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500"
                                placeholder="Adresse de l'entreprise"></textarea>
                            @error('adresse')
                                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ville et Pays --}}
                        <div class="tw-grid tw-grid-cols-2 tw-gap-6">
                            <div>
                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Ville</label>
                                <input type="text" wire:model="ville"
                                    class="tw-mt-1 tw-block tw-w-full tw-rounded-lg tw-border-gray-300 focus:tw-ring-primary-500 focus:tw-border-primary-500"
                                    placeholder="Ex: Antananarivo">
                                @error('ville')
                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Pays</label>
                                <x-country-select :selected="$pays" class="tw-mt-1" />
                                @error('pays')
                                    <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="tw-flex tw-justify-between tw-pt-6 tw-mt-6 tw-border-t tw-border-gray-200">
                            <button type="submit"
                                class="tw-inline-flex tw-items-center tw-px-6 tw-py-2.5 tw-bg-primary-600 tw-text-white tw-rounded-lg hover:tw-bg-primary-700 focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-primary-500">
                                <span>Ajouter l'entreprise</span>
                                <svg class="tw-w-5 tw-h-5 tw-ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/>
                                </svg>
                            </button>

                            @if($showTerminerButton)
                            <button type="button" wire:click="terminer"
                                class="tw-inline-flex tw-items-center tw-px-6 tw-py-2.5 tw-bg-success-600 tw-text-white tw-rounded-lg hover:tw-bg-success-700 focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-success-500">
                                <span>Terminer</span>
                                <svg class="tw-w-5 tw-h-5 tw-ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
