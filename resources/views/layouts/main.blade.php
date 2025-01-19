<!DOCTYPE html>
<html lang="fr" class="tw-h-full tw-scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/clash-display" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="tw-min-h-screen tw-flex tw-flex-col tw-bg-gray-50 dark:tw-bg-gray-900 tw-transition-colors tw-duration-200">

    <x-navbar/>

    <!-- Main Content -->
    <main class="tw-flex-1 tw-mt-0">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @auth
    <!-- Footer -->
    <footer class="tw-mt-auto tw-w-full tw-bg-white/80 dark:tw-bg-gray-800/80 tw-backdrop-blur-lg tw-border-t tw-border-gray-200 dark:tw-border-gray-700">
        <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-h-16 tw-flex tw-items-center tw-justify-between">
                <!-- Left side -->
                <div class="tw-flex tw-items-center tw-gap-6">
                    <a href="#" class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400 hover:tw-text-gray-900 dark:hover:tw-text-white">Support</a>
                    <a href="#" class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400 hover:tw-text-gray-900 dark:hover:tw-text-white">Contact</a>
                </div>

                <!-- Right side -->
                <div class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">
                    © {{ date('Y') }} ERP INNOV
                </div>
            </div>
        </div>
    </footer>
    @endauth

    @stack('modals')
    @livewireScripts
</body>
</html>
