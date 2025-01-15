<!DOCTYPE html>
<html lang="fr" class="tw-h-full tw-bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="tw-min-h-screen tw-flex tw-flex-col">
    <!-- Navbar Fixed -->
    <nav class="tw-fixed tw-top-0 tw-left-0 tw-right-0 tw-z-50 tw-bg-white tw-border-b tw-border-gray-100">
        <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-flex tw-justify-between tw-h-16">
                <!-- Logo -->
                <div class="tw-flex tw-items-center">
                    <a href="/" class="tw-flex tw-items-center">
                        <img class="tw-h-8 tw-w-auto" src="{{ asset('client/assets/img/logo/logo.png') }}" alt="Logo">
                        <span class="tw-ml-2 tw-text-xl tw-font-bold tw-text-gray-900">ERP INNOV</span>
                    </a>
                </div>
                
                <!-- Navigation droite -->
                <div class="tw-flex tw-items-center tw-space-x-4">
                    @auth
                        <!-- Profile Dropdown -->
                        <div class="tw-relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    @keydown.escape.window="open = false"
                                    class="tw-flex tw-items-center tw-space-x-3 tw-px-3 tw-py-2 tw-rounded-md hover:tw-bg-gray-50 focus:tw-outline-none">
                                <img class="tw-h-8 tw-w-8 tw-rounded-full tw-object-cover" 
                                     src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                                     alt="{{ Auth::user()->name }}">
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false"
                                 class="tw-absolute tw-right-0 tw-mt-2 tw-w-48 tw-rounded-md tw-shadow-lg tw-bg-white tw-ring-1 tw-ring-black tw-ring-opacity-5"
                                 x-transition:enter="tw-transition tw-ease-out tw-duration-100"
                                 x-transition:enter-start="tw-transform tw-opacity-0 tw-scale-95"
                                 x-transition:enter-end="tw-transform tw-opacity-100 tw-scale-100"
                                 x-transition:leave="tw-transition tw-ease-in tw-duration-75"
                                 x-transition:leave-start="tw-transform tw-opacity-100 tw-scale-100"
                                 x-transition:leave-end="tw-transform tw-opacity-0 tw-scale-95">
                                <div class="tw-py-1">
                                    <a href="{{ route('profile.show') }}" 
                                       class="tw-block tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-100">
                                        Mon Profil
                                    </a>
                                    <a href="{{ route('entreprise.create') }}" 
                                       class="tw-block tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-100">
                                        Mes Entreprises
                                    </a>
                                    <div class="tw-border-t tw-border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="tw-block tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-100">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" 
                           class="tw-text-gray-700 hover:tw-text-gray-900 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-md hover:tw-bg-gray-50 tw-transition">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" 
                           class="tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-md tw-transition">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="tw-flex-1 tw-min-h-screen"> <!-- Marges pour navbar et footer -->
        {{ $slot }}
    </main>

    <!-- Footer Fixed -->
    @auth
        <footer class="tw-fixed tw-bottom-0 tw-left-0 tw-right-0 tw-bg-white tw-border-t tw-border-gray-100 tw-z-50">
            <div class="tw-h-16 tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-flex tw-items-center tw-justify-center">
                <div class="tw-flex tw-items-center tw-space-x-6">
                    <a href="#" class="tw-text-sm tw-text-gray-500 hover:tw-text-gray-900 tw-transition">Support</a>
                    <span class="tw-w-1 tw-h-1 tw-bg-gray-300 tw-rounded-full"></span>
                    <span class="tw-text-sm tw-text-gray-500">© {{ date('Y') }} ERP INNOV</span>
                    <span class="tw-w-1 tw-h-1 tw-bg-gray-300 tw-rounded-full"></span>
                    <a href="#" class="tw-text-sm tw-text-gray-500 hover:tw-text-gray-900 tw-transition">Contact</a>
                </div>
            </div>
        </footer>
    @endauth
    <!-- Scripts et Modals -->
    @stack('modals')
    @livewireScripts
</body>
</html>