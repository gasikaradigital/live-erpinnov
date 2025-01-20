<x-main-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                Créer un compte gratuit
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                En créant un compte, vous acceptez nos
                <a href="{{ route('terms.show') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    conditions d'utilisation
                </a> et notre
                <a href="{{ route('policy.show') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    politique de confidentialité
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <!-- Auth Options -->
                <div id="auth-options" class="space-y-4">
                    <div>
                        <a href="/auth/google"
                           class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032 s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2 C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                            </svg>
                            Utiliser avec votre compte Google
                        </a>
                    </div>

                    <div>
                        <button
                            type="button"
                            onclick="toggleRegistrationForm()"
                            class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            S'inscrire avec votre email
                        </button>
                    </div>
                </div>

                <!-- Registration Form (Hidden by default) -->
                <div id="registration-form" class="hidden">
                    <div class="mt-6 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Remplissez le formulaire</span>
                        </div>
                    </div>

                    <form class="mt-6 space-y-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom complet
                            </label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" required
                                       class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"
                                       value="{{ old('name') }}" />
                                <x-input-error for="name" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Adresse email
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" required
                                       class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"
                                       value="{{ old('email') }}" />
                                <x-input-error for="email" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Mot de passe
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                       class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm" />
                                <x-input-error for="password" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmer le mot de passe
                            </label>
                            <div class="mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm" />
                                <x-input-error for="password_confirmation" class="mt-2" />
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <x-checkbox name="terms" id="terms" required />

                                        <div class="ms-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                        @endif

                        <div>
                            <button type="submit"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                Créer un compte
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <button
                            type="button"
                            onclick="toggleRegistrationForm()"
                            class="text-sm text-primary-600 hover:text-primary-500">
                            Retour aux options de connexion
                        </button>
                    </div>
                </div>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Déjà inscrit?
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la gestion de l'affichage -->
    <script>
        function toggleRegistrationForm() {
            const authOptions = document.getElementById('auth-options');
            const registrationForm = document.getElementById('registration-form');

            if (authOptions.classList.contains('hidden')) {
                authOptions.classList.remove('hidden');
                registrationForm.classList.add('hidden');
            } else {
                authOptions.classList.add('hidden');
                registrationForm.classList.remove('hidden');
            }
        }
    </script>
</x-main-layout>
