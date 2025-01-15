{{-- views/livewire/client/modal/create-instance-modal.blade.php --}}
<div>
    @include('livewire.client.messages.styleLoader')

    <div wire:ignore.self class="modal fade" id="instanceModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 p-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="location.reload();"></button>
                </div>
                @if($newInstanceInfo)
                    {{-- Voici information après création instance --}}
                    @include('livewire.client.messages.infocreated')
                @else
                    <!-- Formulaire de création d'instance -->
                    <div class="modal-body px-4 pb-4">
                        @if(!$showPlanSelection)
                            <div class="text-center mb-4">
                                <h4 class="fw-semibold mb-2">Créez votre espace de travail</h4>
                                <p class="text-secondary mb-0">
                                    En quelques clics, créez votre environnement professionnel personnalisé
                                </p>
                            </div>

                            <form wire:submit.prevent="store">
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Nom de l'instance</label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                                               wire:model="name"
                                               placeholder="votreinstance">
                                        <span class="input-group-text bg-light">.erpinnov.com</span>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4m0 4h.01" stroke-linecap="round"/>
                </svg>
                <span>Ce nom sera utilisé pour accéder à votre instance</span>
            </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Votre entreprise</label>
                                    <select wire:model.live="entreprise_id"
                                            class="form-select form-select-lg @error('entreprise_id') is-invalid @enderror">
                                        <option value="">Sélectionnez votre entreprise</option>
                                        @foreach($entreprises as $entreprise)
                                            <option value="{{ $entreprise->id }}">{{ $entreprise->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('entreprise_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($entreprise_id)
                                    <div class="mb-4">
                                        <div class="d-inline-flex align-items-center gap-2 bg-light rounded-3 p-3">
                                            <img src="{{ asset('assets/img/flags/' . ($selectedPays == 'Madagascar' ? '0.png' : '1.png')) }}"
                                                 alt="{{ $selectedPays }}"
                                                 width="24"
                                                 class="rounded">
                                            <div>
                                                <span class="fw-medium">{{ $selectedPays }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-3">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <span>Créer l'instance</span>
                                            <i class="ti ti-arrow-right"></i>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <div class="d-inline-flex p-3 rounded-circle bg-light mb-3">
                                    <i class="ti ti-alert-circle text-danger fs-2 opacity-75"></i>
                                </div>
                                <h5 class="fw-semibold mb-2">Limite d'instances atteinte</h5>
                                <p class="text-secondary mb-4">
                                    Passez à un forfait supérieur pour créer plus d'instances
                                </p>
                                <button class="btn btn-primary px-4 rounded-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#pricingModal">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="ti ti-package"></i>
                                        <span>Voir les forfaits</span>
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="overlay" wire:loading wire:target="store">
        <div class="spinner-container">
            <div class="sk-wave sk-primary">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>
            <p class="loading-text mt-1">Création de l'instance en cours...</p>
            <p class="waiting-text">Veuillez patienter, svp</p>
        </div>
    </div>
</div>

