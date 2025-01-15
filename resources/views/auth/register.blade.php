<x-main-layout>
    <div class="tw-min-h-screen tw-flex tw-flex-col tw-justify-center tw-py-12 sm:tw-px-6 lg:tw-px-8">
        <div class="sm:tw-mx-auto sm:tw-w-full sm:tw-max-w-md">
            <h2 class="tw-mt-6 tw-text-center tw-text-3xl tw-font-bold tw-tracking-tight tw-text-gray-900">
                Créer un compte gratuit
            </h2>
            <p class="tw-mt-2 tw-text-center tw-text-sm tw-text-gray-600">
                En créant un compte, vous acceptez nos
                <a href="{{ route('terms.show') }}" class="tw-font-medium tw-text-blue-600 hover:tw-text-blue-500">
                    conditions d'utilisation
                </a> et notre
                <a href="{{ route('policy.show') }}" class="tw-font-medium tw-text-blue-600 hover:tw-text-blue-500">
                    politique de confidentialité
                </a>
            </p>
        </div>

        <div class="tw-mt-8 sm:tw-mx-auto sm:tw-w-full sm:tw-max-w-md">
            <div class="tw-bg-white tw-py-8 tw-px-4 tw-shadow sm:tw-rounded-lg sm:tw-px-10">
                <!-- Auth Options -->
                <div id="auth-options" class="tw-space-y-4">
                    <div>
                        <a href="/auth/google" 
                           class="tw-w-full tw-flex tw-justify-center tw-items-center tw-px-4 tw-py-3 tw-border tw-border-gray-300 tw-shadow-sm tw-text-sm tw-font-medium tw-rounded-md tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition">
                            <svg class="tw-h-5 tw-w-5 tw-mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032 s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2 C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                            </svg>
                            Utiliser avec votre compte Google
                        </a>
                    </div>

                    <div>
                        <button 
                            type="button"
                            onclick="toggleRegistrationForm()"
                            class="tw-w-full tw-flex tw-justify-center tw-items-center tw-px-4 tw-py-3 tw-border tw-border-gray-300 tw-shadow-sm tw-text-sm tw-font-medium tw-rounded-md tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="tw-h-5 tw-w-5 tw-mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            S'inscrire avec votre email
                        </button>
                    </div>
                </div>

                <!-- Registration Form (Hidden by default) -->
                <div id="registration-form" class="tw-hidden">
                    <div class="tw-mt-6 tw-relative">
                        <div class="tw-absolute tw-inset-0 tw-flex tw-items-center">
                            <div class="tw-w-full tw-border-t tw-border-gray-300"></div>
                        </div>
                        <div class="tw-relative tw-flex tw-justify-center tw-text-sm">
                            <span class="tw-px-2 tw-bg-white tw-text-gray-500">Remplissez le formulaire</span>
                        </div>
                    </div>

                    <form class="tw-mt-6 tw-space-y-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div>
                            <label for="name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Nom complet
                            </label>
                            <div class="tw-mt-1">
                                <input id="name" name="name" type="text" required 
                                       class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm"
                                       value="{{ old('name') }}" />
                                <x-input-error for="name" class="tw-mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Adresse email
                            </label>
                            <div class="tw-mt-1">
                                <input id="email" name="email" type="email" required
                                       class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm"
                                       value="{{ old('email') }}" />
                                <x-input-error for="email" class="tw-mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Mot de passe
                            </label>
                            <div class="tw-mt-1">
                                <input id="password" name="password" type="password" required
                                       class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm" />
                                <x-input-error for="password" class="tw-mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Confirmer le mot de passe
                            </label>
                            <div class="tw-mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm" />
                                <x-input-error for="password_confirmation" class="tw-mt-2" />
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
        
                                    <div class="ms-2">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif
                    
                        <div>
                            <button type="submit"
                                    class="tw-w-full tw-flex tw-justify-center tw-py-3 tw-px-4 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-blue-600 hover:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-blue-500 tw-transition">
                                Créer un compte
                            </button>
                        </div>
                    </form>

                    <div class="tw-mt-4 tw-text-center">
                        <button 
                            type="button"
                            onclick="toggleRegistrationForm()"
                            class="tw-text-sm tw-text-blue-600 hover:tw-text-blue-500"
                        >
                            Retour aux options de connexion
                        </button>
                    </div>
                </div>

                <p class="tw-mt-6 tw-text-center tw-text-sm tw-text-gray-600">
                    Déjà inscrit?
                    <a href="{{ route('login') }}" class="tw-font-medium tw-text-blue-600 hover:tw-text-blue-500">
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
            
            if (authOptions.classList.contains('tw-hidden')) {
                authOptions.classList.remove('tw-hidden');
                registrationForm.classList.add('tw-hidden');
            } else {
                authOptions.classList.add('tw-hidden');
                registrationForm.classList.remove('tw-hidden');
            }
        }
    </script>
</x-main-layout>