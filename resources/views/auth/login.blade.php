<x-main-layout>
    <div class="tw-min-h-screen tw-flex tw-flex-col tw-justify-center tw-py-12 sm:tw-px-6 lg:tw-px-8">
        <div class="sm:tw-mx-auto sm:tw-w-full sm:tw-max-w-md">
            <div class="tw-bg-white tw-py-8 tw-px-4 tw-shadow sm:tw-rounded-lg sm:tw-px-10">
                <!-- Logo -->
                <div class="tw-flex tw-justify-center tw-items-center tw-mb-6">
                    <a href="/" class="tw-flex tw-items-center tw-gap-2">
                        <img src="{{ asset('client/assets/img/logo/logo.png') }}"
                            alt="Logo ERP INNOV"
                            class="tw-w-10 tw-h-10 tw-object-contain">
                        <span class="tw-text-xl tw-font-bold tw-text-gray-900">ERP INNOV</span>
                    </a>
                </div>

                <!-- Validation Errors -->
                <x-validation-errors class="tw-mb-4" />

                <!-- Session Status -->
                @session('status')
                    <div class="tw-mb-4 tw-text-sm tw-font-medium tw-text-red-600">
                        {{ $value }}
                    </div>
                @endsession

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="tw-space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                            Email
                        </label>
                        <div class="tw-mt-1">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                autocomplete="email"
                                placeholder="Entrer votre email"
                                value="{{ old('email') }}"
                                class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm"
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Mot de passe
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="tw-text-sm tw-text-blue-600 hover:tw-text-blue-500">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="tw-mt-1 tw-relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required
                                placeholder="Mot de passe"
                                class="tw-block tw-w-full tw-appearance-none tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-2 tw-placeholder-gray-400 tw-shadow-sm focus:tw-border-blue-500 focus:tw-outline-none focus:tw-ring-blue-500 sm:tw-text-sm"
                            />
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="tw-absolute tw-inset-y-0 tw-right-0 tw-pr-3 tw-flex tw-items-center tw-text-gray-400 hover:tw-text-gray-500">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="tw-h-5 tw-w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="tw-flex tw-items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox"
                            class="tw-h-4 tw-w-4 tw-text-blue-600 focus:tw-ring-blue-500 tw-border-gray-300 tw-rounded"
                        />
                        <label for="remember" class="tw-ml-2 tw-block tw-text-sm tw-text-gray-700">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit"
                            class="tw-w-full tw-flex tw-justify-center tw-py-2 tw-px-4 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-blue-600 hover:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-blue-500 tw-transition">
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="tw-mt-6 tw-text-center tw-text-sm tw-text-gray-600">
                    Nouveau sur notre plateforme ?
                    <a href="/register" class="tw-font-medium tw-text-blue-600 hover:tw-text-blue-500">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for password toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" /><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />`;
            }
        }
    </script>
</x-main-layout>