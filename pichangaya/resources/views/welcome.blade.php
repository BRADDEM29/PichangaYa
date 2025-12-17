<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO METADATA --}}
    <title>PichangaYa | Reserva Canchas de Fútbol, Vóley y más en Cusco</title>
    <meta name="description" content="Reserva canchas deportivas en Cusco al instante. Fútbol, Vóley, Básquet. Encuentra canchas con Grass Sintético, Techo, Wifi y Estacionamiento.">
    <meta name="keywords" content="alquiler canchas cusco, pichangaya, reservar futbol, voley cusco, canchas sinteticas">
    <meta property="og:title" content="PichangaYa - Reserva tu cancha ideal">
    <meta property="og:description" content="La forma más rápida de encontrar y reservar canchas deportivas en Cusco.">
    <meta property="og:type" content="website">

    {{-- SCRIPT PARA EVITAR FLASH BLANCO (DARK MODE) --}}
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- SCRIPTS Y ESTILOS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    {{-- FONDO DECORATIVO --}}
    <div class="fixed inset-0 z-[-1]">
        <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-10 dark:opacity-5" alt="Fondo texturizado deportes">
    </div>

    @include('navigation-menu')

    {{-- 1. HERO SECTION --}}
    <div class="relative bg-gray-900 text-white overflow-hidden pb-32">
        <div class="absolute inset-0">
            <img src="{{ asset('images/balon2.webp') }}" class="w-full h-full object-cover opacity-40" alt="">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <header class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8 text-center z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 drop-shadow-xl">
                Encuentra tu cancha en <span class="text-green-400">Cusco</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto font-medium">
                Fútbol, Vóley, Básquet y más. Reserva al instante.
            </p>

            {{-- FORMULARIO DE BÚSQUEDA --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-2xl max-w-4xl mx-auto text-gray-900 dark:text-gray-100">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    
                    <div class="md:col-span-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre de la cancha..." 
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-green-500 focus:border-green-500 placeholder-gray-400">
                    </div>

                    <div class="md:col-span-3">
                        <select name="district_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-green-500 focus:border-green-500 text-gray-700 dark:text-gray-200">
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
                        <select name="sport_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-green-500 focus:border-green-500 text-gray-700 dark:text-gray-200">
                            <option value="">Todos los Deportes</option>
                            @isset($sports)
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                        {{ $sport->icon }} {{ $sport->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition duration-200 shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </header>

        @if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request()->has('search'))
            <div class="relative z-10 mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="bg-yellow-500 w-2 h-8 rounded-full"></span>
                        CANCHAS DESTACADAS
                    </h2>
                    <x-carousel :items="$featuredCanchas" />
                </div>
            </div>
        @endif

        <div class="absolute bottom-[-1px] left-0 right-0 w-full overflow-hidden leading-[0] rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[70px] md:h-[120px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50 dark:fill-gray-950"></path>
            </svg>
        </div>
    </div>

    {{-- 2. LISTADO DE RESULTADOS --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white border-l-4 border-green-600 pl-4">
                Explora Todas las Canchas
            </h2>
        </div>

        @if($canchas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-100 dark:border-gray-700">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">No encontramos canchas</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Intenta seleccionando otro distrito o deporte.</p>
                <a href="{{ route('home') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Ver todas las canchas
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($canchas as $cancha)
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                        
                        <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas', 'large') }}" 
                                     alt="{{ $cancha->name }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                     loading="lazy">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-sm font-bold text-green-700 dark:text-green-400 border border-green-100 dark:border-green-900">
                                S/ {{ number_format($cancha->price_per_hour, 2) }} <span class="text-xs font-normal text-gray-500">/hr</span>
                            </div>
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 transition-colors">
                                {{ $cancha->name }}
                            </h3>

                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    📍 {{ $cancha->district->name ?? 'Cusco' }}
                                </span>

                                @if($cancha->sports && $cancha->sports->count() > 0)
                                    @foreach($cancha->sports as $sport)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-600 text-white shadow-sm ring-1 ring-green-600 ring-offset-1 dark:ring-offset-gray-800">
                                            {{ $sport->icon }} {{ $sport->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            @if($cancha->services && $cancha->services->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-4 pt-2 border-t border-gray-50 dark:border-gray-700">
                                    @foreach($cancha->services as $service)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 uppercase tracking-wide">
                                            <span>{{ $service->icon }}</span>
                                            <span>{{ $service->name }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                                {{ $cancha->description ?? 'Disfruta del deporte en las mejores instalaciones.' }}
                            </p>

                            <div class="mt-auto">
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="block w-full py-2.5 bg-gray-900 dark:bg-green-600 text-white text-center rounded-lg font-bold hover:bg-green-600 dark:hover:bg-green-500 transition shadow hover:shadow-md">
                                    Ver Disponibilidad
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h4 class="text-2xl font-bold mb-2">⚽ Pichanga<span class="text-green-500">Ya</span></h4>
            <p class="text-gray-400 text-sm mb-4">La plataforma #1 para reservar canchas en Cusco.</p>
            <p class="text-gray-600 text-xs">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>