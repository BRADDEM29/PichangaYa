<!DOCTYPE html>
{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\welcome.blade.php --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO METADATA --}}
    <title>PichangaYa | Reserva Canchas de Fútbol, Vóley y más en Cusco</title>
    <meta name="description" content="Reserva canchas deportivas en Cusco al instante. Fútbol, Vóley, Básquet. Encuentra canchas con Grass Sintético, Techo, Wifi y Estacionamiento.">

    {{-- DARK MODE SCRIPT --}}
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js (Defer) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-950 dark:text-gray-100">

    @include('navigation-menu')

    {{-- ZONA SUPERIOR: HERO + CARRUSEL + OLA AL FINAL --}}
    <div class="relative bg-gray-900 text-white overflow-hidden">
        
        {{-- 1. HERO & SEARCH --}}
        <div class="relative pb-16">
            <div class="absolute inset-0">
                <img src="{{ asset('images/balon2.webp') }}" class="w-full h-full object-cover opacity-40" alt="Fútbol Cusco">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
            </div>

            <header class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8 text-center z-10">
                <h1 id="hero-title" class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 drop-shadow-xl">
                    Encuentra tu cancha en <span class="text-green-400">Cusco</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto font-medium">
                    Fútbol, Vóley, Básquet y más. Reserva al instante.
                </p>

                <div id="search-form" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-2xl max-w-4xl mx-auto text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre de la cancha..." 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-green-500 focus:border-green-500 placeholder-gray-400">
                        </div>
                        <div class="md:col-span-3">
                            <select name="district_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg">
                                <option value="">Todo Cusco</option>
                                @isset($districts)
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <select name="sport_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg">
                                <option value="">Todos los Deportes</option>
                                @isset($sports)
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                            {{ $sport->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition duration-200 shadow-md flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </header>
        </div>

        {{-- 2. CARRUSEL DESTACADOS --}}
        @if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request('search'))
            <div id="featured-section" class="relative pb-28 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <h3 class="text-2xl font-black text-white uppercase tracking-wide">Mejores Canchas</h3>
                    </div>
                    <x-carousel :items="$featuredCanchas" />
                </div>
            </div>
        @endif

        {{-- 🌊 LA OLA --}}
        <div class="absolute bottom-[-1px] left-0 right-0 w-full overflow-hidden leading-[0] rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[70px] md:h-[120px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50 dark:fill-gray-950"></path>
            </svg>
        </div>
    </div>

    {{-- 2.5. SECCIÓN NUEVA: MIS FAVORITOS (Extraído) --}}
    @auth
    @livewire('home.favorites-list')
    @endauth

    {{-- 3. LISTADO DE CANCHAS (Extraído) --}}
    {{-- IMPORTANTE: Pasamos la variable $canchas al componente --}}
    <x-home.canchas-grid :canchas="$canchas" />

    {{-- 4. SECCIÓN ¿QUIÉNES SOMOS? --}}
    <section id="about-section" class="py-20 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="relative">
                    <span class="text-green-600 font-bold uppercase tracking-widest text-sm mb-2 block">Conócenos</span>
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-8">¿Quiénes somos?</h2>
                    <div class="space-y-6 text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                        <p>
                            Somos la plataforma #1 en Cusco diseñada para simplificar la vida de los deportistas. Nuestra misión es conectar la pasión por el deporte con la tecnología, ofreciendo una forma rápida y segura de reservar espacios deportivos.
                        </p>
                        <p>
                            Ya sea fútbol, vóley o básquet, trabajamos con los mejores complejos para garantizar que tu única preocupación sea dar lo mejor en la cancha.
                        </p>
                    </div>
                    <div class="mt-10 grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span> 
                            Reservas al instante
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span> 
                            Pagos seguros
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-4 bg-green-500/10 rounded-3xl transform rotate-3 transition group-hover:rotate-0"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/balon2.webp') }}" alt="PichangaYa Cusco" class="w-full h-[400px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl hidden md:block">
                        <div class="text-3xl font-black text-green-600">100%</div>
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Cusqueño</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- COMPONENTE MODULAR DE FOOTER --}}
    <x-footer />
    {{-- El componente DEBE estar aquí, justo antes del final --}}
    <x-floating-buttons />

    <x-urgent-booking-card />

    @livewireScripts
</body>
</html>