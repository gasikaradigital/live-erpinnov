<!-- Affichage des informations après création -->
<div class="modal-body px-4" x-data="{
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
    <div class="text-center mb-4">
        <div class="success-icon">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <circle cx="24" cy="24" r="24" fill="#E8F5E9"/>
                <path d="M16 24L22 30L32 20" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
        </div>
        <h4 class="fw-semibold mt-3 mb-2">Instance créée avec succès !</h4>
        <p class="text-secondary">Voici les informations de connexion de votre nouvelle instance</p>
    </div>

    <div class="bg-light rounded-3 p-4">
        <div class="row g-4">
            <!-- Nom de l'instance -->
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="icon-box">
                        <i class="ti ti-apps"></i>
                    </div>
                    <div>
                        <div class="text-secondary small">Nom de l'instance</div>
                        <div class="fw-medium">{{ $newInstanceInfo['name'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Login -->
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="icon-box">
                        <i class="ti ti-user"></i>
                    </div>
                    <div>
                        <div class="text-secondary small">Login</div>
                        <div class="fw-medium">{{ $newInstanceInfo['login'] }}</div>
                    </div>
                </div>
            </div>

    <!-- Mot de passe -->
    <div class="col-md-6">
        <div class="d-flex gap-3">
            <div class="icon-box">
                <i class="ti ti-key"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-secondary small">Mot de passe</div>
                <div class="d-flex gap-2">
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        value="{{ $newInstanceInfo['password'] }}" 
                        class="pwd-input"
                        readonly>
                    <button type="button" 
                            class="action-btn" 
                            @click="showPassword = !showPassword"
                            :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'">
                        <i class="ti" :class="showPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                    </button>
                    <button type="button" 
                            class="action-btn" 
                            @click="copyToClipboard('{{ $newInstanceInfo['password'] }}', 'password')"
                            aria-label="Copier le mot de passe">
                        <i class="ti" :class="copyFeedback.password ? 'ti-check' : 'ti-copy'"
                           :style="copyFeedback.password ? 'color: #4CAF50' : ''"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- URL -->
    <div class="col-md-6">
        <div class="d-flex gap-3">
            <div class="icon-box">
                <i class="ti ti-link"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-secondary small">URL de connexion</div>
                <div class="d-flex gap-2">
                    <a href="{{ $newInstanceInfo['url'] }}" 
                       target="_blank" 
                       class="text-primary text-decoration-none flex-grow-1">
                        {{ $newInstanceInfo['url'] }}
                    </a>
                    <button type="button" 
                            class="action-btn" 
                            @click="copyToClipboard('{{ $newInstanceInfo['url'] }}', 'url')"
                            aria-label="Copier l'URL">
                        <i class="ti" :class="copyFeedback.url ? 'ti-check' : 'ti-copy'"
                           :style="copyFeedback.url ? 'color: #4CAF50' : ''"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
   

    <div class="alert alert-warning border-0 d-flex align-items-start gap-2 my-4">
        <i class="ti ti-alert-circle"></i>
        <div>Veuillez sauvegarder ces informations dans un endroit sûr. Vous les recevrez également par email.</div>
    </div>

    <div class="text-center">
        <a href="{{ $newInstanceInfo['url'] }}" 
           class="btn btn-primary px-4 py-2" 
           target="_blank">
            Accéder à mon instance
            <i class="ti ti-external-link ms-2"></i>
        </a>
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush



<style>
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    border: none;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #6C757D;
    transition: 0.2s;
}

.action-btn:hover {
    background: #EDF2FF;
    color: #0D6EFD;
}

.pwd-input {
    border: none;
    background: transparent;
    font-weight: 500;
    padding: 0;
    margin: 0;
    outline: none;
    min-width: 120px;
}

.icon-box {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0D6EFD;
}
</style>