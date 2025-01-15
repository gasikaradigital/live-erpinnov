<div> {{-- Élément racine unique --}}
    <div class="tw-min-h-screen tw-flex">
        <div class="tw-flex tw-w-full">
            {{-- Left Text --}}
            <div class="tw-hidden lg:tw-flex lg:tw-w-1/3 tw-items-center tw-justify-center tw-p-5 tw-bg-gray-50 tw-relative">
                <img src="{{ asset('client/assets/img/illustrations/auth-register-multisteps-illustration.png')}}" 
                     alt="auth-register-multisteps" 
                     class="tw-w-80 tw-h-60 tw-object-contain" />
            </div>
            
            {{-- Multi Steps Registration --}}
            <div class="tw-flex lg:tw-w-2/3 tw-items-center tw-justify-center tw-bg-white tw-p-5">
                <div class="tw-max-w-3xl tw-w-full">
                    <form wire:submit.prevent="store">
                        @csrf
                        {{-- Personal Info --}}
                        <div class="tw-space-y-6">
                            <div class="tw-mb-6">
                                <h4 class="tw-text-xl tw-font-semibold tw-mb-0">Renseignements de l'entreprise</h4>
                                <p class="tw-text-gray-600 tw-mb-0">Entrez vos informations</p>
                            </div>

                            @if($entreprises->isNotEmpty())
                                <div class="tw-bg-blue-50 tw-border tw-border-blue-200 tw-text-blue-800 tw-p-4 tw-rounded-lg tw-flex tw-items-center tw-mt-3">
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

                            <div class="tw-grid tw-gap-6">
                                <div class="tw-col-span-full">
                                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="name">Nom de l'entreprise</label>
                                    <input type="text" id="name" wire:model="name" 
                                           class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500" 
                                           placeholder="SpaceX" required />
                                    @error('name') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-6">
                                    <div>
                                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="nif">NIF <small>(facultatif)</small></label>
                                        <input type="text" id="nif" wire:model="nif" 
                                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500" 
                                               placeholder="4789206500022" maxlength="18"/>
                                        @error('nif') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="phone">Téléphone</label>
                                        <input type="text" id="phone" wire:model="phone" 
                                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500" 
                                               placeholder="202 555 0111" required />
                                        @error('phone') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="tw-col-span-full">
                                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="adresse">Adresse</label>
                                    <input type="text" id="adresse" wire:model="adresse" 
                                           class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500" 
                                           placeholder="Address" required />
                                    @error('adresse') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-6">
                                    <div>
                                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="ville">Ville</label>
                                        <input type="text" id="ville" wire:model="ville" 
                                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500" 
                                               placeholder="Mahajanga" required />
                                        @error('ville') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700" for="pays">Pays</label>
                                        <x-country-select :selected="$pays" />
                                        @error('pays') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="tw-flex tw-justify-between tw-mt-4">
                                    <button type="submit" 
                                            class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-font-medium tw-rounded-md tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500">
                                        <span class="tw-mr-2">Ajouter</span>
                                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>

                                    @if($showTerminerButton)
                                        <button type="button" wire:click="terminer"
                                                class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-font-medium tw-rounded-md tw-text-white tw-bg-green-600 hover:tw-bg-green-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-green-500">
                                            <span class="tw-mr-2">Terminer</span>
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>