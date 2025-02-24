<!-- Section Essai Gratuit ou Mise à Niveau -->
@if($hasUsedTrial)
<div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-2xl overflow-hidden shadow-xl relative">
    <div class="p-8 text-white">
        <!-- En-tête -->
        <div class="space-y-4 max-w-2xl">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm font-medium backdrop-blur-sm">
                    @if(!$hasUsedTrial)
                        ✨ Deux options pour commencer
                    @else
                        ✨ Votre forfait
                    @endif
                </span>
            </div>

            <div class="space-y-2">
                @if(!$hasUsedTrial)
                    <h2 class="text-3xl font-bold">Choisissez votre formule</h2>
                    <p class="text-lg text-white/90">Démarrez avec un essai gratuit ou abonnez-vous directement</p>
                @else
                    <h2 class="text-3xl font-bold">Abonnez-vous maintenant</h2>
                    <p class="text-lg text-white/90">Profitez de toutes les fonctionnalités sans limitation</p>
                @endif
            </div>

            <!-- Boutons d'action -->
            <div class="@if(!$hasUsedTrial) grid grid-cols-1 md:grid-cols-2 gap-4 @endif mt-8">
                <!-- Bouton Abonnement -->
                <button
                    x-data=""
                    x-on:click="$dispatch('open-payment-modal')"
                    class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    <span>@if(!$hasUsedTrial) Faire un abonnement @else S'abonner maintenant @endif</span>
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </button>

                <!-- Bouton Essai Gratuit (affiché uniquement si l'essai n'a pas été utilisé) -->
                @if(!$hasUsedTrial)
                    <button
                        wire:click="startTrial"
                        class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white/10 transition-all duration-300 transform hover:scale-105">
                        <span>Démarrer l'essai gratuit</span>
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@elseif($currentInstance)
<div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-2xl overflow-hidden shadow-xl relative">
    <div class="p-8 text-white">
        <!-- En-tête -->
        <div class="space-y-4 max-w-2xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm font-medium backdrop-blur-sm">
                ⚡ Options de mise à niveau
            </span>

            <div class="space-y-2">
                <h2 class="text-2xl font-bold">Passez à la version payante</h2>
                <p class="text-white/90">Choisissez comment continuer avec votre abonnement ({{ $currentInstance->remainingTrialDays }} jours restants d'essai)</p>
            </div>

            <!-- Information Instance Actuelle -->
            <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm mb-6">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium block">{{ $currentInstance->name }}.erpinnov.com</span>
                        <span class="text-sm text-white/80">{{ $currentInstance->remainingTrialDays }} jours restants d'essai</span>
                    </div>
                </div>
            </div>

            <!-- Bouton de mise à niveau (un seul bouton, car nous mettons à niveau l’instance existante) -->
            <div class="mt-6">
                <button
                    wire:click="processPayment"
                    x-data=""
                    x-on:click="$dispatch('open-payment-modal')"
                    class="w-full inline-flex items-center justify-center px-6 py-4 bg-white text-blue-600 rounded-xl font-medium hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block font-semibold">Mettre à niveau l'instance</span>
                            <span class="text-sm text-blue-500">Passez à la version payante</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
