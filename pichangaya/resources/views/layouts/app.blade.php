<!DOCTYPE html>
{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\layouts\app.blade.php --}}
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PichangaYa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @php
        $canUseDarkMode = auth()->check() && auth()->user()->isUser();
    @endphp

    <script>
        const canUseDarkMode = @json($canUseDarkMode);
        
        if (canUseDarkMode) {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <x-strike-warning-overlay />
    
    @livewire('strike-warning')

    <div class="min-h-screen">
        @livewire('navigation-menu')

        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    {{-- 🟢 AQUÍ ESTABA EL ERROR: Faltaba esta línea para cargar los gráficos --}}
    @stack('scripts')

    <x-urgent-booking-card />
</body>
</html>