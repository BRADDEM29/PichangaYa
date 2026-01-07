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

    {{-- 🟡 1. LÓGICA PHP: DETECTAR RUTAS PROHIBIDAS (Login, Admin, Dueño) 🟡 --}}
    @php
        $forceLightMode = request()->routeIs([
            'login', 'register', 'password.*', 'two-factor.*',
            'admin.*', 'owner.*', 'profile.show'
        ]) || request()->is([
            'admin/*', 'panel-dueno/*', 'user/profile'
        ]);
    @endphp

    {{-- 🟡 2. SCRIPT GUARDIÁN (MutationObserver) 🟡 --}}
    <script>
        const forceLightMode = @json($forceLightMode);

        // A. Si estamos en ruta forzada, ACTIVAMOS EL GUARDIÁN
        if (forceLightMode) {
            // 1. Quitar clase inmediatamente
            document.documentElement.classList.remove('dark');
            
            // 2. Crear un vigilante que impida que Alpine u otros scripts agreguen 'dark'
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class' && document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        // console.log('🚫 Modo oscuro bloqueado por el Guardián.');
                    }
                });
            });
            
            // 3. Empezar a vigilar <html>
            observer.observe(document.documentElement, { attributes: true });

        } else {
            // B. Comportamiento normal (respetar preferencia de usuario)
            if (localStorage.getItem('dark-mode') === 'true' || 
                (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

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
    
    {{-- 🟢 INICIO PRELOADER PICHANGA PRO (Con AlpineJS) --}}
    <div x-data="{ loaded: false }" 
         x-init="window.addEventListener('load', () => { setTimeout(() => loaded = true, 800) })" 
         x-show="!loaded"
         x-transition:leave="transition ease-in duration-700"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 translate-y-[-100%]"
         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gray-100 dark:bg-[#0f172a] text-gray-800 dark:text-white">
        
        {{-- Contenedor de la animación --}}
        <div class="relative flex items-center justify-center mb-6">
            
            {{-- 1. Anillos giratorios (Decoración Estadio) --}}
            <div class="absolute w-40 h-40 rounded-full border-4 border-t-green-500 border-r-transparent border-b-green-600 border-l-transparent animate-spin"></div>
            <div class="absolute w-32 h-32 rounded-full border-2 border-t-transparent border-r-green-300 border-b-transparent border-l-green-300 animate-spin-slow opacity-60"></div>

            {{-- 2. CÍRCULO BLANCO (Solo en Modo Oscuro) --}}
            {{-- MAGIA: bg-transparent por defecto, pero bg-white y shadow en dark mode --}}
            <div class="relative z-10 p-4 rounded-full transition-all duration-300
                        bg-transparent 
                        dark:bg-white 
                        dark:shadow-[0_0_30px_rgba(255,255,255,0.6)]">
                
                <img src="{{ asset('images/Pichanga-_1_.webp') }}" 
                     alt="Cargando..." 
                     class="w-20 h-20 object-contain animate-pulse">
            </div>
        </div>

        {{-- Texto --}}
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-black tracking-[0.2em] font-digital">
                PICHANGA<span class="text-green-600 dark:text-green-400">YA</span>
            </h2>
            <div class="flex items-center justify-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                <span class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                <span class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        .animate-spin-slow {
            animation: spin-reverse 3s linear infinite;
        }
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
    {{-- 🔴 FIN PRELOADER --}}

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
    @stack('scripts')
    
</body>
</html>