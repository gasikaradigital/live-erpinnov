{{-- resources/views/profile/edit.blade.php --}}
<x-main-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-8"> {{-- Réduit à max-w-3xl --}}
            <!-- En-tête -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profil</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Mettez à jour vos informations personnelles
                </p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Informations de base -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="fname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
                                <input type="text" name="fname" id="fname" value="{{ old('fname', $profile->fname) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="fname" class="mt-1" />
                            </div>

                            <div>
                                <label for="lname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                                <input type="text" name="lname" id="lname" value="{{ old('lname', $profile->lname) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="lname" class="mt-1" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexe</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="inline-flex items-center p-2 border rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ old('sexe', $profile->sexe) === 'homme' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600' }}">
                                        <input type="radio"
                                               name="sexe"
                                               value="homme"
                                               {{ old('sexe', $profile->sexe) === 'homme' ? 'checked' : '' }}
                                               class="form-radio h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Homme</span>
                                    </label>
                                    <label class="inline-flex items-center p-2 border rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ old('sexe', $profile->sexe) === 'femme' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600' }}">
                                        <input type="radio"
                                               name="sexe"
                                               value="femme"
                                               {{ old('sexe', $profile->sexe) === 'femme' ? 'checked' : '' }}
                                               class="form-radio h-4 w-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Femme</span>
                                    </label>
                                </div>
                                <x-input-error for="sexe" class="mt-1" />
                            </div>

                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $profile->telephone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="telephone" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $profile->adresse) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <x-input-error for="adresse" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-2 gap-6 sm:grid-cols-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="zipcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code postal</label>
                                <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode', $profile->zipcode) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="zipcode" class="mt-1" />
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="ville" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville', $profile->ville) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="ville" class="mt-1" />
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="pays" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pays</label>
                                <input type="text" name="pays" id="pays" value="{{ old('pays', $profile->pays) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="pays" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                            <textarea name="bio" id="bio" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('bio', $profile->bio) }}</textarea>
                            <x-input-error for="bio" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de naissance</label>
                                <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $profile->birthdate?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="birthdate" class="mt-1" />
                            </div>

                            <div>
                                <label for="birthlocation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu de naissance</label>
                                <input type="text" name="birthlocation" id="birthlocation" value="{{ old('birthlocation', $profile->birthlocation) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <x-input-error for="birthlocation" class="mt-1" />
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $profile->is_public) ? 'checked' : '' }}
                                       class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_public" class="font-medium text-gray-700 dark:text-gray-300">Profil public</label>
                                <p class="text-gray-500 dark:text-gray-400">Rendre votre profil visible pour les autres utilisateurs</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150">
                        Mettre à jour
                    </button>
                </div>
            </form>

            @if(session('status'))
            <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
                {{ session('status') }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Notification
        const notification = document.getElementById('notification');
        if (notification) {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Formatage téléphone
        const phoneInput = document.getElementById('telephone');
        phoneInput.addEventListener('input', function(e) {
            let phone = e.target.value.replace(/\D/g, '');
            if (phone.length > 10) phone = phone.substring(0, 10);
            if (phone.length >= 2) {
                phone = phone.match(new RegExp('.{1,2}', 'g')).join(' ');
            }
            e.target.value = phone;
        });
    </script>
    @endpush
</x-main-layout>
