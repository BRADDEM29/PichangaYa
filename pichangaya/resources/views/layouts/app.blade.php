<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PichangaYa'))</title>
    @stack('meta')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- 1. SCRIPT CRÍTICO: Evita el "flashazo" blanco antes de cargar el CSS --}}
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- 2. ESTILOS GLOBALES Y REGLA MAESTRA PARA MODO OSCURO --}}
    <style>
        /* REGLA MAESTRA: Si es modo oscuro, nada puede ser fondo blanco */
        .dark .bg-white {
            background-color: #111827 !important; /* gray-900 */
            color: #ffffff !important;
        }

        /* Forzar visibilidad de textos en Jetstream y Layouts */
        .dark .text-gray-400, 
        .dark .text-gray-500, 
        .dark .text-gray-600, 
        .dark .text-gray-700, 
        .dark .text-gray-800,
        .dark .text-gray-900 {
            color: #d1d5db !important; /* gray-300 para párrafos */
        }

        .dark h1, .dark h2, .dark h3, .dark h4, .dark .font-bold, .dark .font-black {
            color: #ffffff !important; /* Blanco puro para títulos */
        }

        /* Ajustar bordes globales para que no desaparezcan en el fondo negro */
        .dark .border-gray-100, 
        .dark .border-gray-200, 
        .dark .border-gray-300,
        .dark .border-gray-700 {
            border-color: #374151 !important; /* gray-700 */
        }

        /* Estilo para Inputs, Textareas y Selects (Visibilidad total) */
        .dark input, .dark textarea, .dark select {
            background-color: #030712 !important; /* Un poco más oscuro que el fondo (gray-950) */
            color: #ffffff !important;
            border-color: #4b5563 !important; /* gray-600 */
        }

        .dark input::placeholder {
            color: #6b7280 !important;
        }

        .dark label {
            color: #ffffff !important;
        }

        /* Suavizado de sombras para que no brillen raro en oscuro */
        .dark .shadow-sm, .dark .shadow, .dark .shadow-md, .dark .shadow-lg, .dark .shadow-xl {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        /* Corrección para Navigation Menu (si usa bg-white) */
        .dark nav.bg-white {
            background-color: #111827 !important;
            border-bottom: 1px solid #374151 !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 dark:text-white transition-colors duration-300"
      x-data="{ isLoading: true }"
      x-init="window.addEventListener('load', () => { setTimeout(() => isLoading = false, 1000) })">

    {{-- EL LOADER --}}
    <x-loader />

    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        @livewire('navigation-menu')

        {{-- Page Heading adaptable --}}
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow border-b dark:border-gray-700 transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- Page Content --}}
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    {{-- Script para reactivar loader en links internos --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    // Solo activar loader si es un link interno real
                    if (href && href.startsWith('/') && !href.startsWith('#') && !link.target) {
                        try { 
                            // Acceder al scope de Alpine para mostrar el loader
                            document.querySelector('body').__x.$data.isLoading = true; 
                        } catch(e){}
                    }
                });
            });
        });
    </script>
</body>
</html>