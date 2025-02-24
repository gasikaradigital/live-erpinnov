<!DOCTYPE html>
<html lang="fr" class="h-full scroll-smooth dark:color-scheme-dark">
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
    @stack("styles")
</head>
<body class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <x-navbar/>

    <!-- Main Content -->
    <main class="flex-1 mt-0">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @auth
    <!-- Footer -->
    <footer class="mt-auto w-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <!-- Left side -->
                <div class="flex items-center gap-6">
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Support</a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Contact</a>
                </div>

                <!-- Right side -->
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} ERP INNOV
                </div>
            </div>
        </div>
    </footer>
    @endauth

    @stack('modals')
    @stack("scripts")
    @livewireScripts

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />

</body>
</html>
