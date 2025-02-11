<button type="submit"
        wire:loading.attr="disabled"
        class="inline-flex items-center mt-3 gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-xl text-white font-medium shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transform hover:-translate-y-0.5 transition-all duration-300">
    <!-- Texte normal et loading -->
    <span wire:loading.remove>
        Créer l'instance
    </span>
    <span wire:loading>
        Création en cours...
    </span>

    <!-- Icône flèche (visible quand pas de loading) -->
    {{-- <svg wire:loading.remove
         class="w-4 h-4"
         fill="none"
         stroke="currentColor"
         viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M14 5l7 7m0 0l-7 7m7-7H3"/>
    </svg> --}}

    <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>

    <!-- Spinner (visible pendant le loading) -->
    <svg wire:loading
         class="animate-spin w-4 h-4"
         fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"/>
        <path class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
    </svg>
</button>
