<template>
    <nav class="layout-navbar container shadow-none py-0">
        <div class="navbar navbar-expand-lg landing-navbar border-top-0 px-3 px-md-4">
            <!-- Logo -->
            <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4">
                <button class="navbar-toggler border-0 px-0 me-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <i class="tf-icons ri-menu-fill ri-24px align-middle"></i>
                </button>

                <a href="/" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="/assets/img/front-pages/logo/logo.png"
                             alt="Logo ERP INNOV"
                             style="width: 40px; height: 40px;"
                             class="img-fluid">
                    </span>
                    <span class="app-brand-text demo menu-text fw-semibold ms-2">
                        ERP <span class="innov-text">INNOV</span>
                    </span>
                </a>
            </div>

            <!-- Menu principal -->
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto p-3 p-lg-0">
                    <li v-for="item in menuItems"
                        :key="item.href"
                        class="nav-item mx-2">
                        <a class="nav-link fw-medium px-3 d-flex align-items-center gap-2"
                           :href="item.href"
                           :class="{ 'active': activeSection === item.href }">
                            <i :class="['tf-icons', item.icon]"></i>
                            <span>{{ item.text }}</span>
                        </a>
                    </li>

                    <!-- Connexion et Inscription -->
                    <li class="nav-item mx-2" v-if="!auth">
                        <a href="/login" class="auth-link btn-login">
                            <i class="tf-icons ri-user-line me-md-1"></i>
                            <span class="d-inline">Connexion</span>
                        </a>
                    </li>
                    <li class="nav-item mx-2" v-if="!auth">
                        <a href="/inscription" class="auth-link btn-inscription">
                            <i class="tf-icons ri-user-add-line me-md-1"></i>
                            <span class="d-inline">Inscription</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<style>
/* Style du texte ERP Innov */
.innov-text {
    color: #0393da;
}

/* Bureau : Les boutons restent normaux */
@media (min-width: 992px) {
    .btn-login, .btn-inscription {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn-login {
        background-color: #0d6efd; /* Bleu */
        border: 2px solid #0d6efd;
        color: white;
    }

    .btn-login:hover {
        background-color: white;
        color: #0d6efd;
    }

    .btn-inscription {
        background-color: #FF8C00; /* Orange */
        border: 2px solid #FF8C00;
        color: white;
    }

    .btn-inscription:hover {
        background-color: white;
        color: #FF8C00;
    }
}

/* Mobile : Transformer en liens */
@media (max-width: 991.98px) {
    .landing-nav-menu {
        display: flex;
        width: fit-content !important; /* Ajuste automatiquement à la taille du contenu */
        max-width: 70%; /* Limite la largeur max pour éviter qu'il prenne toute la ligne */
        margin: 0 auto; /* Centre le menu */
    }
    .navbar-nav {
        display: flex;
        flex-direction: column;
        align-items: left;
    }

    .auth-link {
        display: block;
        text-align: left;
        width: 100%;
        padding: 12px 0;
        font-weight: bold;
        text-decoration: none;
    }

    .btn-login {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
    }

    .btn-inscription {
        color: #FF8C00;
        border-bottom: 2px solid #FF8C00;
    }

    /* Effet au survol */
    .btn-login:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-inscription:hover {
        background-color: #FF8C00;
        color: white;
    }
}
</style>

<script>
export default {
    name: 'NavSection',
    data() {
        return {
            auth: window.Laravel.auth,
            activeSection: '',
            menuItems: [
                { href: '#landingHero', icon: 'ri-home-line', text: 'Accueil' },
                { href: '#landingPricing', icon: 'ri-price-tag-line', text: 'Offres' },
                { href: '#landingGestion', icon: 'ri-price-tag-line', text: 'Gestion' },
                { href: '#landingFAQ', icon: 'ri-questionnaire-line', text: 'FAQ' }
            ]
        }
    },
    mounted() {
        this.handleScroll()
        window.addEventListener('scroll', this.handleScroll)
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll)
    },
    methods: {
        handleScroll() {
            const sections = this.menuItems.map(item => item.href.substring(1))
            const scrollPosition = window.scrollY + 100

            for (const section of sections) {
                const element = document.getElementById(section)
                if (element) {
                    const { top, bottom } = element.getBoundingClientRect()
                    if (top <= 100 && bottom >= 100) {
                        this.activeSection = '#' + section
                        break
                    }
                }
            }
        },
        async logout() {
            try {
                await axios.post('/logout')
                window.location.reload()
            } catch (error) {
                console.error('Erreur de déconnexion:', error)
            }
        }
    }
}
</script>
