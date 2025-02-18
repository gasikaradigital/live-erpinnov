<div class="min-h-screen bg-gray-50 dark:bg-gray-900 mt-12">
    <div class="flex mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 gap-8">
        <!-- Sidebar - Liste des entreprises -->

        <div class="hidden lg:block w-80 flex-shrink-0">
            <div class="sticky top-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                            Mes orgnaisations ({{ $entreprises->count() }})
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[calc(100vh-16rem)] overflow-y-auto">
                        @foreach($entreprises as $entreprise)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-700 dark:text-primary-300 font-semibold">
                                    {{ substr($entreprise->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $entreprise->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $entreprise->ville }}, {{ $entreprise->pays }}</p>
                                </div>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- Contenu principal -->
        <div class="flex-1 max-w-3xl">
            {{-- Formulaire --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-500 to-primary-600">
                    <h2 class="text-xl font-semibold text-white">Nouvelle orgnaisation</h2>
                    <p class="mt-2 text-primary-100">Remplissez les informations pour ajouter une entreprise.</p>
                </div>

                <form wire:submit.prevent="store" class="p-6 space-y-6">
                    {{-- Nom de l'organisation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom de l'organisation</label>
                        <div class="relative rounded-lg">
                            <input type="text" wire:model="name"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 pl-4 pr-10 transition-colors"
                                placeholder="Ex: Space X">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIF/SIREN et Téléphone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIF ou SIREN</label>
                            <input type="text" wire:model="nif"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Numéro fiscal">
                            @error('nif')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Téléphone</label>
                            <input type="tel" wire:model="phone"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="+261 xx xx xxx xx">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nombre d'employés --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre d'employés</label>
                        <select wire:model="employees_count"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Sélectionnez</option>
                            <option value="0-5">0-5 employés</option>
                            <option value="1-10">1-10 employés</option>
                            <option value="11-50">11-50 employés</option>
                            <option value="51-200">51-200 employés</option>
                            <option value="201+">201+ employés</option>
                        </select>
                        @error('employees_count')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse complète</label>
                        <textarea wire:model="adresse" rows="3"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Adresse de l'entreprise"></textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ville et Pays --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ville</label>
                            <input type="text" wire:model="ville"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Ex: Antananarivo">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pays</label>
                            <x-country-select :selected="$pays" class="mt-1" />
                            @error('pays')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="flex justify-between pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-150">
                            <span>Ajouter l'entreprise</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>

                        @if($showTerminerButton)
                            <button type="button" wire:click="terminer"
                            class="inline-flex items-center px-6 py-3 bg-success-600 text-white rounded-lg hover:bg-success-700 focus:ring-2 focus:ring-offset-2 focus:ring-success-500 transition-all duration-150">
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
</div>

@push('scripts')
<script>
// Animation de la notification
const notification = document.getElementById('notification');
if (notification) {
    setTimeout(() => {
        notification.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Formatage du numéro de téléphone
const phoneInput = document.querySelector('input[wire\\:model="phone"]');
if (phoneInput) {
    phoneInput.addEventListener('input', function(e) {
        let phone = e.target.value.replace(/\D/g, '');
        if (phone.length > 10) {
            phone = phone.substring(0, 10);
        }
        if (phone.length >= 2) {
            phone = phone.match(new RegExp('.{1,2}', 'g')).join(' ');
        }
        // Utiliser Livewire pour mettre à jour la valeur
        Livewire.find(phoneInput.closest('[wire\\:id]').getAttribute('wire:id'))
            .set('phone', phone);
    });
}

// Confirmation de suppression d'entreprise
document.querySelectorAll('[wire\\:click*="deleteEntreprise"]').forEach(button => {
    button.addEventListener('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Animation pour les notifications */
@keyframes slideIn {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.notification-enter {
    animation: slideIn 0.3s ease-out;
}

/* Style pour la scrollbar de la sidebar */
.max-h-\[calc\(100vh-16rem\)\] {
    scrollbar-width: thin;
    scrollbar-color: theme('colors.gray.400') theme('colors.gray.100');
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar {
    width: 6px;
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-track {
    background: theme('colors.gray.100');
    border-radius: 3px;
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-thumb {
    background-color: theme('colors.gray.400');
    border-radius: 3px;
}

.dark .max-h-\[calc\(100vh-16rem\)\] {
    scrollbar-color: theme('colors.gray.600') theme('colors.gray.800');
}

.dark .max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-track {
    background: theme('colors.gray.800');
}

.dark .max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-thumb {
    background-color: theme('colors.gray.600');
}
</style>
@endpush
