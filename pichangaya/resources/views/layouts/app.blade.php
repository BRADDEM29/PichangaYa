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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

{{-- Lógica de Alpine para controlar la carga --}}
<body class="font-sans antialiased"
      x-data="{ isLoading: true }"
      x-init="window.addEventListener('load', () => { setTimeout(() => isLoading = false, 1000) })">

    {{-- EL LOADER VA AQUÍ, ARRIBA DE TODO --}}
    <x-loader />

    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        @if (isset($header))
            <header class="bg-white shadow">
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

    {{-- Script para reactivar loader en links internos --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && href.startsWith('/') && !href.startsWith('#') && !link.target) {
                        try { document.querySelector('body').__x.$data.isLoading = true; } catch(e){}
                    }
                });
            });
        });
    </script>
</body>
</html>