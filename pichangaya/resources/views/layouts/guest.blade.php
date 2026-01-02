<!DOCTYPE html>
<html lang="es"> {{-- Idioma español --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PichangaYa') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            document.documentElement.classList.remove('dark');
        </script>

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
    </head>
    <body>
        {{-- PANTALLA DE CARGA (PRELOADER) --}}
        <div id="global-loader" class="fixed inset-0 z-[9999] bg-gray-100 flex flex-col items-center justify-center">
            <div class="relative">
                <img src="{{ asset('images/Pichanga-_1_.webp') }}" 
                     alt="Cargando..." 
                     class="w-32 h-32 object-contain animate-bounce drop-shadow-2xl">
                <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-16 h-2 bg-black/10 rounded-[100%] blur-sm animate-pulse"></div>
            </div>
            <p class="mt-4 text-green-600 font-bold text-sm tracking-widest animate-pulse">CARGANDO...</p>
        </div>

        <div class="font-sans text-gray-900 antialiased bg-gray-100">
            {{ $slot }}
        </div>

        @livewireScripts

        <script>
            // Lógica del Preloader
            window.addEventListener('load', function() {
                const loader = document.getElementById('global-loader');
                if (loader) {
                    setTimeout(() => {
                        loader.classList.add('loader-hidden');
                    }, 500); // Pequeño retardo para suavidad
                }
            });

            // Mostrar loader al salir de la página (navegar)
            window.addEventListener('beforeunload', function () {
                const loader = document.getElementById('global-loader');
                if (loader) {
                    loader.classList.remove('loader-hidden');
                }
            });
        </script>
    </body>
</html>