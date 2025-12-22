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
                            @if($cancha->media->isNotEmpty())
                                <img src="{{ asset('storage/' . $cancha->media->first()->file_path) }}" 
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

                            {{-- ETIQUETAS DE CANCHAS (Como antes con emojis) --}}
                            <div class="flex flex-wrap items-center gap-2 mb-2">
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

                            {{-- ETIQUETAS DE SERVICIOS (Abajo de las etiquetas de canchas) --}}
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach ($cancha->services as $service)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50">
                                        {{ $service->icon }} {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>

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

    {{-- SÚPER FOOTER MEJORADO --}}
    <footer class="bg-white dark:bg-gray-950 text-gray-800 dark:text-gray-300 pt-16 pb-8 border-t border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                {{-- Columna 1: Branding y Redes Sociales --}}
                <div class="space-y-4">
                    <h4 class="text-2xl font-black italic text-gray-900 dark:text-white">
                        ⚽ Pichanga<span class="text-green-500">Ya</span>
                    </h4>
                    <p class="text-sm leading-relaxed opacity-80">
                        La plataforma #1 en Cusco para peloteros. Encuentra, reserva y juega en las mejores canchas de la ciudad imperial.
                    </p>
                    
                    <div class="flex space-x-5 pt-4">
                        <a href="#" class="text-gray-400 hover:text-blue-600 transition-all transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-pink-600 transition-all transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.669-.072-4.948-.2-4.358-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://wa.me/51940766968" target="_blank" class="text-gray-400 hover:text-green-500 transition-all transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Columna 2: Enlaces Rápidos --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Explorar</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-green-500 transition">🔍 Buscar Canchas</a></li>
                        <li><a href="{{ route('register-pitch') }}" class="text-green-600 dark:text-green-400 font-bold hover:underline">🏟️ Registra tu Cancha</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-green-500 transition">¿Quiénes somos?</a></li>
                    </ul>
                </div>

                {{-- Columna 3: Soporte --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Ayuda</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('faq') }}" class="hover:text-green-500 transition">Preguntas Frecuentes</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Atención Inmediata</a></li>
                        <li><a href="{{ route('suggestions.index') }}" class="hover:text-green-500 transition">Enviar Sugerencia</a></li>
                    </ul>
                </div>

                {{-- Columna 4: Legal --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Legal</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('terms.show') }}" class="hover:text-green-500 transition">Términos y Condiciones</a></li>
                        <li><a href="{{ route('policy.show') }}" class="hover:text-green-500 transition">Privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center">
                <p class="text-xs opacity-60">
                    &copy; {{ date('Y') }} PichangaYa Cusco. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>