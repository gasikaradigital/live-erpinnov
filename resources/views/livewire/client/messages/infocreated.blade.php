<!-- Affichage des informations après création -->
<div class="modal-body px-6 py-8" x-data="{
    showPassword: false,
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
}">
    <!-- En-tête avec icône de succès -->
    <div class="text-center mb-8">
        <div class="mb-4">
            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center shadow-md">
                <svg class="w-10 h-10 text-green-600" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        <h4 class="text-2xl font-bold text-green-500 dark:text-white">Instance créée avec succès !</h4>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Voici les informations de connexion de votre nouvelle instance</p>
    </div>

    <!-- Grille d'informations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- Nom de l'instance -->
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300">
                    <use href="#grid"></use>
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Nom de l'instance</span>
                <div class="font-medium text-gray-900 dark:text-white">{{ $newInstanceInfo['name'] }}</div>
            </div>
        </div>
        <!-- URL -->
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300">
                    <use href="#link"></use>
                </svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">URL de connexion</span>
                <div class="flex items-center gap-2">
                    <a href="{{ $newInstanceInfo['url'] }}"
                       target="_blank"
                       class="text-indigo-600 hover:text-indigo-800 font-medium">
                        {{ $newInstanceInfo['url'] }}
                    </a>
                    <button
                        @click="copyToClipboard('{{ $newInstanceInfo['url'] }}', 'url')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500"
                        :class="{ 'text-green-500': copyFeedback.url }">
                        <svg x-show="!copyFeedback.url" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        <svg x-show="copyFeedback.url" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mot de passe -->
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300">
                    <use href="#lock"></use>
                </svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Mot de passe</span>
                <div class="flex items-center gap-2">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        value="{{ $newInstanceInfo['password'] }}"
                        class="bg-transparent border-none p-0 font-medium text-gray-900 dark:text-white outline-none"
                        readonly>
                    <button
                        @click="showPassword = !showPassword"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500">
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                        </svg>
                    </button>
                    <button
                        @click="copyToClipboard('{{ $newInstanceInfo['password'] }}', 'password')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500"
                        :class="{ 'text-green-500': copyFeedback.password }">
                        <svg x-show="!copyFeedback.password" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        <svg x-show="copyFeedback.password" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Login -->
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300">
                    <use href="#user"></use>
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Login</span>
                <div class="font-medium text-gray-900 dark:text-white">{{ $newInstanceInfo['login'] }}</div>
            </div>
        </div>
    </div>

    <!-- Alerte -->
    <div class="mt-6 flex items-center gap-3 p-4 bg-yellow-100 dark:bg-yellow-700 rounded-lg shadow">
        <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-300 flex-shrink-0">
            <use href="#alert-triangle"></use>
        </svg>
        <p class="text-sm text-blue-200 dark:text-gray-200">
            Veuillez sauvegarder ces informations dans un endroit sûr. Vous les recevrez également par email.
        </p>
    </div>

    <!-- Bouton d'accès -->
    <div class="mt-8 text-center">
        <a href="{{ $newInstanceInfo['url'] }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all">
            Accéder à mon instance
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</div>
<!-- Script pour les icônes -->
<svg class="hidden">
    <symbol id="grid" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
    </symbol>
    <symbol id="user" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </symbol>
    <symbol id="lock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
    </symbol>
    <symbol id="link" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
    </symbol>
</svg>
