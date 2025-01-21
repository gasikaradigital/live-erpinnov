<button type="submit"
        wire:loading.attr="disabled"
        wire:target="submit"
        class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-xl text-sm font-medium text-white disabled:opacity-50 disabled:cursor-not-allowed">

    <span wire:loading.remove wire:target="submit">Créer l'instance</span>
    <span wire:loading wire:target="submit">Création en cours...</span>

    <!-- Spinner simplifié -->
    <svg wire:loading
         wire:target="submit"
         class="animate-spin h-4 w-4"
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4">
        </circle>
        <path class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
        </path>
    </svg>
</button>
