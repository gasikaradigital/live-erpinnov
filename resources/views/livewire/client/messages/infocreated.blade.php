<div x-data="{
    showPassword: false,
    isVerifying: true,
    copyFeedback: {
        password: false,
        url: false
    },
    async copyToClipboard(text, type) {
        try {
            await navigator.clipboard.writeText(text);
            this.copyFeedback[type] = true;
            setTimeout(() => {
                this.copyFeedback[type] = false;
            }, 1500);
        } catch (err) {
            console.error('Erreur de copie:', err);
        }
    }
}"
    class="tw-bg-white dark:tw-bg-gray-800 tw-shadow tw-rounded-lg tw-p-6 tw-relative"
    wire:poll.5s="checkInstanceStatus"
    x-init="$wire.checkInstanceStatus().then(status => { if(status) isVerifying = false })">

    <template x-if="isVerifying">
        <div class="tw-absolute tw-inset-0 tw-bg-white/90 dark:tw-bg-gray-800/90 tw-flex tw-items-center tw-justify-center tw-z-50 tw-rounded-lg">
            <div class="tw-text-center">
                <div class="tw-flex tw-justify-center tw-mb-4">
                    <svg class="tw-animate-spin tw-h-10 tw-w-10 tw-text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="tw-text-lg tw-font-medium tw-text-gray-900 dark:tw-text-white">
                    Vérification de votre instance en cours
                </h3>
                <p class="tw-mt-2 tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">
                    Veuillez patienter pendant que nous finalisons la configuration...
                </p>
            </div>
        </div>
    </template>

    <!-- En-tête avec icône de succès -->
    <div class="tw-text-center tw-mb-6">
        <div class="tw-mb-4">
            <div class="tw-w-16 tw-h-16 tw-mx-auto tw-bg-success-50 tw-rounded-full tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-8 tw-h-8 tw-text-success-500" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        <h4 class="tw-text-xl tw-font-semibold tw-mb-2 tw-text-gray-900 dark:tw-text-white">Instance créée avec succès !</h4>
        <p class="tw-text-gray-500 dark:tw-text-gray-400">Voici les informations de connexion de votre nouvelle instance</p>
    </div>

    <!-- Grille d'informations -->
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-bg-gray-50 dark:tw-bg-gray-700/50 tw-rounded-xl tw-p-6">
        <!-- Nom de l'instance -->
        <div class="tw-flex tw-items-start tw-gap-4">
            <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-primary-50 dark:tw-bg-primary-900/50 tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-5 tw-h-5 tw-text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <div>
                <span class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Nom de l'instance</span>
                <div class="tw-font-medium tw-text-gray-900 dark:tw-text-white">{{ $newInstanceInfo['name'] }}</div>
            </div>
        </div>

        <!-- Login -->
        <div class="tw-flex tw-items-start tw-gap-4">
            <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-primary-50 dark:tw-bg-primary-900/50 tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-5 tw-h-5 tw-text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <span class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Login</span>
                <div class="tw-font-medium tw-text-gray-900 dark:tw-text-white">{{ $newInstanceInfo['login'] }}</div>
            </div>
        </div>

        <!-- Mot de passe -->
        <div class="tw-flex tw-items-start tw-gap-4">
            <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-primary-50 dark:tw-bg-primary-900/50 tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-5 tw-h-5 tw-text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div class="tw-flex-1">
                <span class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Mot de passe</span>
                <div class="tw-flex tw-items-center tw-gap-2">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        value="{{ $newInstanceInfo['password'] }}"
                        class="tw-bg-transparent tw-border-none tw-p-0 tw-font-medium tw-text-gray-900 dark:tw-text-white tw-outline-none"
                        readonly>
                    <button
                        @click="showPassword = !showPassword"
                        class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-600 tw-text-gray-500">
                        <svg x-show="!showPassword" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                    <button
                        @click="copyToClipboard('{{ $newInstanceInfo['password'] }}', 'password')"
                        class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-600 tw-text-gray-500"
                        :class="{ 'tw-text-success-500': copyFeedback.password }">
                        <svg x-show="!copyFeedback.password" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <svg x-show="copyFeedback.password" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- URL -->
        <div class="tw-flex tw-items-start tw-gap-4">
            <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-primary-50 dark:tw-bg-primary-900/50 tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-5 tw-h-5 tw-text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <div class="tw-flex-1">
                <span class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">URL de connexion</span>
                <div class="tw-flex tw-items-center tw-gap-2">
                    <a href="{{ $newInstanceInfo['url'] }}"
                       target="_blank"
                       class="tw-text-primary-600 hover:tw-text-primary-700 tw-font-medium">
                        {{ $newInstanceInfo['url'] }}
                    </a>
                    <button
                        @click="copyToClipboard('{{ $newInstanceInfo['url'] }}', 'url')"
                        class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-600 tw-text-gray-500"
                        :class="{ 'tw-text-success-500': copyFeedback.url }">
                        <svg x-show="!copyFeedback.url" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <svg x-show="copyFeedback.url" class="tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerte -->
    <div class="tw-mt-6 tw-flex tw-items-start tw-gap-3 tw-p-4 tw-bg-warning-50 dark:tw-bg-warning-900/50 tw-rounded-lg">
        <svg class="tw-w-5 tw-h-5 tw-text-warning-500 tw-flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="tw-text-sm tw-text-warning-800 dark:tw-text-warning-200">
            Veuillez sauvegarder ces informations dans un endroit sûr. Vous les recevrez également par email.
        </p>
    </div>

    <!-- Bouton d'accès -->
    <div class="tw-mt-6 tw-text-center">
        <a href="{{ $newInstanceInfo['url'] }}"
           target="_blank"
           class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-3 tw-bg-primary-600 hover:tw-bg-primary-700 tw-text-white tw-font-medium tw-rounded-lg tw-transition-colors">
            Accéder à mon instance
            <svg class="tw-w-5 tw-h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
