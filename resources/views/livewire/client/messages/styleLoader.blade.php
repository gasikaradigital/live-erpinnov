<style>
    .help-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: var(--gray-500);
    font-size: 0.75rem;
    }

    :root {
        --primary-color: #6366f1;
        --border-color: #e2e8f0;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
    }

    /* Styles Modal */
    .modal-content {
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem;
    }

    .btn-close {
        transition: transform 0.2s;
    }

    .btn-close:hover {
        transform: rotate(90deg);
    }

    /* Styles Formulaire */
    .form-control-lg, .form-select-lg {
        min-height: 3rem;
        font-size: 0.95rem;
        border-color: var(--border-color);
    }

    .input-group-merge .form-control:not(:last-child) {
        border-right: 0;
    }

    .input-group-text {
        background-color: var(--bg-light);
        border-color: var(--border-color);
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* Styles Pays */
    .pays-container {
        margin-top: 0.5rem;
    }

    .pays-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background-color: var(--bg-light);
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }

    .pays-badge:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    .flag-img {
        width: 1.5rem;
        height: 1.125rem;
        border-radius: 0.25rem;
        margin-right: 0.75rem;
    }

    .pays-info {
        display: flex;
        flex-direction: column;
    }

    .pays-label {
        font-size: 0.6875rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.125rem;
    }

    .pays-name {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
    }

    /* Overlay de chargement */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-body {
            padding: 1rem !important;
        }

        .pays-badge {
            padding: 0.5rem 0.75rem;
        }

        .flag-img {
            width: 1.25rem;
            height: 1rem;
        }
    }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner-container {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .loading-text, .waiting-text {
        font-size: 18px;
        color: #333;
        font-weight: 500;
        margin: 0;
    }

    .waiting-text {
        font-size: 16px;
        color: #666;
        margin: 0;
    }

    .sk-wave {
        width: 100px; /* Augmenté pour plus de visibilité */
        height: 60px; /* Augmenté pour plus de visibilité */
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
    }

    .sk-wave-rect {
        background-color: #696cff;
        height: 100%;
        width: 15%;
        animation: sk-wave 1.2s infinite ease-in-out;
    }

    .sk-wave-rect:nth-child(1) { animation-delay: -1.2s; }
    .sk-wave-rect:nth-child(2) { animation-delay: -1.1s; }
    .sk-wave-rect:nth-child(3) { animation-delay: -1.0s; }
    .sk-wave-rect:nth-child(4) { animation-delay: -0.9s; }
    .sk-wave-rect:nth-child(5) { animation-delay: -0.8s; }

    @keyframes sk-wave {
        0%, 40%, 100% { transform: scaleY(0.4); }
        20% { transform: scaleY(1.0); }
    }


</style>
