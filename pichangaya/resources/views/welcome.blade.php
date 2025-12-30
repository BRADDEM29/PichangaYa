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
                                            {{ $sport->icon }} {{ $sport->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition duration-200 shadow-md">
                                🔍 Buscar
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
                        <span class="text-3xl">⭐</span>
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

    {{-- 2.5. SECCIÓN NUEVA: MIS FAVORITOS --}}
    @auth
        @if(auth()->user()->favorites->isNotEmpty())
        <section class="py-10 bg-green-50 dark:bg-green-900/10 border-b border-green-100 dark:border-green-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-3xl animate-pulse">❤️</span>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tus Canchas Favoritas</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach(auth()->user()->favorites as $favCancha)
                        <a href="{{ route('canchas.show', $favCancha) }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden">
                            <div class="h-32 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                                @if($favCancha->getFirstMediaUrl('canchas'))
                                    <img src="{{ $favCancha->getFirstMediaUrl('canchas') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex items-center justify-center h-full text-2xl">🏟️</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-green-600 truncate">{{ $favCancha->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">📍 {{ $favCancha->district->name ?? 'Cusco' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endauth

    {{-- 3. LISTADO DE CANCHAS --}}
    <section id="canchas-grid" class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-l-4 border-green-600 pl-4">
            Explora Todas las Canchas
        </h2>

        @if($canchas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <p class="text-gray-500 dark:text-gray-400">No encontramos canchas con esos filtros. 😢</p>
                <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-4 inline-block font-bold">Ver todas</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($canchas as $cancha)
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                        
                        {{-- IMAGEN + BOTON FAVORITOS + PRECIO --}}
                        <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" 
                                     alt="{{ $cancha->name }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex items-center justify-center h-full text-4xl">🏟️</div>
                            @endif
                            
                            {{-- Precio --}}
                            <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-sm font-bold text-green-700">
                                S/ {{ number_format($cancha->price_per_hour, 2) }}
                            </div>

                            {{-- BOTÓN DE FAVORITOS (Alpine.js) --}}
                            @auth
                                <div class="absolute top-4 left-4 z-20"
                                     x-data="{ 
                                         isFaved: {{ $cancha->isFavoritedBy(auth()->user()) ? 'true' : 'false' }},
                                         isLoading: false,
                                         toggleFav() {
                                             this.isLoading = true;
                                             fetch('{{ route('canchas.favorite', $cancha) }}', {
                                                 method: 'POST',
                                                 headers: {
                                                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                     'Content-Type': 'application/json',
                                                     'Accept': 'application/json'
                                                 }
                                             })
                                             .then(response => {
                                                 if (response.ok) {
                                                     this.isFaved = !this.isFaved;
                                                 }
                                             })
                                             .finally(() => {
                                                 this.isLoading = false;
                                             });
                                         }
                                     }">
                                    <button @click="toggleFav()" 
                                            :disabled="isLoading"
                                            class="bg-white/90 dark:bg-gray-900/90 p-2 rounded-full shadow-md hover:scale-110 active:scale-95 transition-all duration-200 group/btn focus:outline-none focus:ring-2 focus:ring-green-400">
                                        
                                        {{-- Corazón Lleno (Rojo) --}}
                                        <svg x-show="isFaved" x-cloak class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        
                                        {{-- Corazón Vacío (Outline) --}}
                                        <svg x-show="!isFaved" x-cloak class="w-6 h-6 text-gray-400 group-hover/btn:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>
                            @endauth
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $cancha->name }}</h3>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-bold px-2 py-1 rounded">
                                    📍 {{ $cancha->district->name ?? 'Cusco' }}
                                </span>
                                @foreach($cancha->sports as $sport)
                                    <span class="bg-green-600 text-white text-[10px] font-bold px-2 py-1 rounded">
                                        {{ $sport->icon }} {{ $sport->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach ($cancha->services as $service)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50">
                                        {{ $service->icon }} {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $cancha->description }}
                            </p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="block w-full py-2.5 bg-green-600 text-white text-center rounded-lg font-bold hover:bg-green-700 transition">
                                    Ver Disponibilidad
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

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
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">✓</span> Reservas al instante
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            <span class="bg-green-100 text-green-600 p-1 rounded-full">✓</span> Pagos seguros
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

    {{-- Componente de Tarjeta Flotante --}}
    <x-urgent-booking-card />
</body>
</html>