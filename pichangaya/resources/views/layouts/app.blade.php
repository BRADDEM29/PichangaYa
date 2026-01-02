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

    <style>
        /* Estilos para el preloader */
        #global-loader {
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>

    {{-- 🟢 INICIO DE AXIOS FIX --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configuración Global de Axios para Laravel
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Adjuntar el Token CSRF automáticamente a cada petición
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }
    </script>
    {{-- 🟢 FIN DE AXIOS FIX --}}

</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    
    {{-- PANTALLA DE CARGA (PRELOADER) --}}
    <div id="global-loader" class="fixed inset-0 z-[9999] bg-gray-50 dark:bg-gray-950 flex flex-col items-center justify-center">
        <div class="relative">
            <img src="{{ asset('images/Pichanga-_1_.webp') }}" 
                 alt="Cargando..." 
                 class="w-32 h-32 object-contain animate-bounce drop-shadow-2xl">
            <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-16 h-2 bg-black/20 dark:bg-white/20 rounded-[100%] blur-sm animate-pulse"></div>
        </div>
        <p class="mt-4 text-green-600 font-bold text-sm tracking-widest animate-pulse">CARGANDO...</p>
    </div>

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

    <x-urgent-booking-card />

    <script>
        // Lógica del Preloader
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                }, 500); 
            }
        });

        // Mostrar loader al cambiar de página
        window.addEventListener('beforeunload', function () {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.remove('loader-hidden');
            }
        });
    </script>
</body>
</html>