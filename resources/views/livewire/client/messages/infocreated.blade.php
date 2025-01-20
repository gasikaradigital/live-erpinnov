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
                <svg class="w-10 h-10 text-green-500" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        <h4 class="text-2xl font-bold text-gray-900 dark:text-white">Instance créée avec succès !</h4>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Voici les informations de connexion de votre nouvelle instance</p>
    </div>

    <!-- Grille d'informations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- Nom de l'instance -->
        @foreach ([
            ['label' => 'Nom de l\'instance', 'value' => $newInstanceInfo['name'], 'icon' => 'grid'],
            ['label' => 'Login', 'value' => $newInstanceInfo['login'], 'icon' => 'user'],
            ['label' => 'Mot de passe', 'value' => $newInstanceInfo['password'], 'icon' => 'lock'],
            ['label' => 'URL de connexion', 'value' => $newInstanceInfo['url'], 'icon' => 'link']
        ] as $item)
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300">
                    <use href="#{{ $item['icon'] }}"></use>
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item['label'] }}</span>
                <div class="font-medium text-gray-900 dark:text-white">{{ $item['value'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Alerte -->
    <div class="mt-6 flex items-center gap-3 p-4 bg-yellow-100 dark:bg-yellow-700 rounded-lg shadow">
        <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-300 flex-shrink-0">
            <use href="#alert-triangle"></use>
        </svg>
        <p class="text-sm text-gray-800 dark:text-gray-200">
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
