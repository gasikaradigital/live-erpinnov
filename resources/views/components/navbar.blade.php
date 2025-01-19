<nav class="tw-fixed tw-inset-x-0 tw-top-0 tw-z-50 tw-backdrop-blur-lg tw-bg-white/80 dark:tw-bg-gray-800/80 tw-border-b tw-border-gray-200 dark:tw-border-gray-700">
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
        <div class="tw-flex tw-justify-between tw-h-16">
            <!-- Logo Section -->
            <div class="tw-flex tw-items-center">
                <a href="/" class="tw-flex tw-items-center tw-gap-3 tw-group">
                    <img class="tw-h-9 tw-w-auto tw-transform tw-transition group-hover:tw-scale-105"
                         src="{{ asset('client/assets/img/logo/logo.png') }}"
                         alt="Logo">
                    <span class="tw-font-display tw-font-semibold tw-text-lg tw-bg-gradient-to-r tw-from-primary-600 tw-to-primary-800 tw-bg-clip-text tw-text-transparent">
                        ERP INNOV
                    </span>
                </a>
            </div>

            <!-- Right Navigation -->
            <div class="tw-flex tw-items-center tw-gap-6">
                @auth
                    <!-- Notifications -->
                    <button class="tw-relative tw-p-2 tw-rounded-full hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700 tw-transition-colors">
                        <span class="tw-absolute tw-top-1 tw-right-1 tw-h-2 tw-w-2 tw-rounded-full tw-bg-primary-600"></span>
                        <svg class="tw-w-6 tw-h-6 tw-text-gray-600 dark:tw-text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="tw-relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @keydown.escape.window="open = false"
                                class="tw-flex tw-items-center tw-gap-3 tw-p-2 tw-rounded-full hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700 tw-transition-colors">
                            <img class="tw-h-9 tw-w-9 tw-rounded-full tw-object-cover tw-ring-2 tw-ring-primary-200 dark:tw-ring-primary-800"
                                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                                 alt="{{ Auth::user()->name }}">
                            <span class="tw-hidden md:tw-block tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-200">
                                {{ Auth::user()->name }}
                            </span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-cloak
                             @click.away="open = false"
                             x-transition:enter="tw-transition tw-ease-out tw-duration-200"
                             x-transition:enter-start="tw-transform tw-opacity-0 tw-scale-95"
                             x-transition:enter-end="tw-transform tw-opacity-100 tw-scale-100"
                             x-transition:leave="tw-transition tw-ease-in tw-duration-75"
                             x-transition:leave-start="tw-transform tw-opacity-100 tw-scale-100"
                             x-transition:leave-end="tw-transform tw-opacity-0 tw-scale-95"
                             class="tw-absolute tw-right-0 tw-mt-2 tw-w-56 tw-rounded-xl tw-bg-white dark:tw-bg-gray-800 tw-shadow-lg tw-ring-1 tw-ring-black/5 dark:tw-ring-white/10 tw-divide-y tw-divide-gray-100 dark:tw-divide-gray-700">
                            <div class="tw-px-2 tw-py-2">
                                <a href="{{ route('profile.show') }}" class="tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2 tw-text-sm tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700/70 tw-text-gray-700 dark:tw-text-gray-300">
                                    <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mon Profil
                                </a>
                                <a href="{{ route('entreprise.create') }}" class="tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2 tw-text-sm tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700/70 tw-text-gray-700 dark:tw-text-gray-300">
                                    <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Mes Entreprises
                                </a>
                            </div>
                            <div class="tw-px-2 tw-py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="tw-flex tw-w-full tw-items-center tw-gap-3 tw-px-3 tw-py-2 tw-text-sm tw-rounded-lg hover:tw-bg-red-50 dark:hover:tw-bg-red-500/10 tw-text-red-600 dark:tw-text-red-400">
                                        <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <a href="{{ route('login') }}"
                           class="tw-text-gray-700 dark:tw-text-gray-300 hover:tw-text-gray-900 dark:hover:tw-text-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700 tw-transition-colors">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}"
                           class="tw-bg-primary-600 hover:tw-bg-primary-700 tw-text-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-shadow-sm hover:tw-shadow">
                            S'inscrire
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
