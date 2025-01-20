<!-- Modal de confirmation de création d'instance -->
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
    <!-- En-tête avec animation de succès -->
    <div class="text-center mb-8">
        <div class="mb-6">
            <div class="w-20 h-20 mx-auto bg-success-50 dark:bg-success-900/30 rounded-full flex items-center justify-center transform transition-all duration-300 hover:scale-105">
                <svg class="w-10 h-10 text-success-500" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">Instance créée avec succès !</h3>
        <p class="text-gray-600 dark:text-gray-300">Voici les informations de connexion de votre nouvelle instance</p>
    </div>

    <!-- Grille d'informations avec effet hover -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
        <!-- Nom de l'instance -->
        <div class="group p-4 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center transform transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom de l'instance</span>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $newInstanceInfo['name'] }}</div>
                </div>
            </div>
        </div>

        <!-- Login -->
        <div class="group p-4 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center transform transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Login</span>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $newInstanceInfo['login'] }}</div>
                </div>
            </div>
        </div>

        <!-- Mot de passe avec animation -->
        <div class="group p-4 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center transform transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mot de passe</span>
                    <div class="flex items-center gap-3 mt-1">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            value="{{ $newInstanceInfo['password'] }}"
                            class="bg-transparent border-none p-0 text-lg font-semibold text-gray-900 dark:text-white outline-none"
                            readonly>
                        <div class="flex gap-2">
                            <button
                                @click="showPassword = !showPassword"
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                            <button
                                @click="copyToClipboard('{{ $newInstanceInfo['password'] }}', 'password')"
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                :class="copyFeedback.password ? 'text-success-500' : 'text-gray-500'">
                                <svg x-show="!copyFeedback.password" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                <svg x-show="copyFeedback.password" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- URL avec animation -->
        <div class="group p-4 rounded-xl transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center transform transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">URL de connexion</span>
                    <div class="flex items-center gap-3 mt-1">
                        <a href="{{ $newInstanceInfo['url'] }}"
                           target="_blank"
                           class="text-lg font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                            {{ $newInstanceInfo['url'] }}
                        </a>
                        <button
                            @click="copyToClipboard('{{ $newInstanceInfo['url'] }}', 'url')"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            :class="copyFeedback.url ? 'text-success-500' : 'text-gray-500'">
                            <svg x-show="!copyFeedback.url" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            <svg x-show="copyFeedback.url" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerte avec animation -->
    <div class="mt-8">
        <div class="flex items-start gap-4 p-5 bg-warning-50 dark:bg-warning-900/30 rounded-xl border border-warning-200 dark:border-warning-700">
            <svg class="w-6 h-6 text-warning-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm text-warning-800 dark:text-warning-200">
                Veuillez sauvegarder ces informations dans un endroit sûr. Vous les recevrez également par email.
            </p>
        </div>
    </div>

<!-- Bouton d'accès avec animation (suite) -->
<div class="mt-8 text-center">
    <a href="{{ $newInstanceInfo['url'] }}"
       target="_blank"
       class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
        <span>Accéder à mon instance</span>
        <svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
    </a>

    <!-- Timestamp et info utilisateur -->
    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
        <p>Créé le {{ date('Y-m-d H:i:s') }} UTC</p>
        <p>par {{ $newInstanceInfo['login'] ?? 'gasikaradigital' }}</p>
    </div>
</div>
</div>

<!-- Toast de notification pour la copie -->
<div x-data="{ show: false, message: '' }"
 @copy-success.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
 class="fixed bottom-4 right-4">
<div x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3">
    <svg class="w-5 h-5 text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <span x-text="message">Copié avec succès!</span>
</div>
</div>

@push('styles')
<style>
/* Animations personnalisées */
@keyframes pulse-success {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

.animate-pulse-success {
    animation: pulse-success 2s ease-in-out infinite;
}

/* Transitions fluides */
.transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Style pour le mode sombre */
.dark .dark\:bg-gradient {
    background: linear-gradient(to bottom right, rgba(55, 65, 81, 0.5), rgba(17, 24, 39, 0.8));
}

/* Effet de survol amélioré */
.hover-effect {
    transition: all 0.3s ease;
}

.hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endpush

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    // Amélioration des animations de copie
    window.addEventListener('copy-success', (event) => {
        const toast = document.querySelector('[x-data*="show"]').__x.$data;
        toast.show = true;
        toast.message = event.detail;
        setTimeout(() => {
            toast.show = false;
        }, 3000);
    });

    // Effet de pulse sur les icônes au survol
    const icons = document.querySelectorAll('.group-hover\\:scale-110');
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', () => {
            icon.classList.add('animate-pulse-success');
        });
        icon.addEventListener('mouseleave', () => {
            icon.classList.remove('animate-pulse-success');
        });
    });
});
</script>
@endpush
