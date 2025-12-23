<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PichangaYa | Reserva Canchas en Cusco</title>
    <meta name="description" content="Reserva canchas deportivas en Cusco al instante. Fútbol, Vóley, Básquet y más.">

    {{-- DARK MODE SCRIPT --}}
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- LIBRERÍAS PARA EL CARRUSEL (Alpine.js) --}}
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

    {{-- 1. HERO & SEARCH --}}
    <div class="relative bg-gray-900 text-white overflow-hidden pb-32">
        <div class="absolute inset-0">
            <img src="{{ asset('images/balon2.webp') }}" class="w-full h-full object-cover opacity-40" alt="Fútbol Cusco">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <header class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-8 text-center z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 drop-shadow-xl">
                Encuentra tu cancha en <span class="text-green-400">Cusco</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto font-medium">
                Fútbol, Vóley, Básquet y más. Reserva al instante.
            </p>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-2xl max-w-4xl mx-auto text-gray-900 dark:text-gray-100">
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

        <div class="absolute bottom-[-1px] left-0 right-0 w-full overflow-hidden leading-[0] rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[70px] md:h-[120px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50 dark:fill-gray-950"></path>
            </svg>
        </div>
    </div>

    {{-- 2. CARRUSEL (SEPARADO) --}}
{{-- 
    MEJORA: Usamos request('search') en lugar de has('search') 
    porque has() devuelve true incluso si el buscador está vacío. 
--}}
@if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request('search') && !request('sport_id') && !request('district_id'))
    <div class="bg-white dark:bg-gray-900 py-12"> {{-- Fondo blanco y separación --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="text-3xl">⭐</span>
               <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wide">Mejores Canchas</h3>
            </div>
            
            {{-- Llamada al componente --}}
            <x-carousel :items="$featuredCanchas" />
            
        </div>
    </div>
@endif

    {{-- 3. LISTADO DE CANCHAS --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-l-4 border-green-600 pl-4">
            Canchas Disponibles
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
                        
                        <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" 
                                     alt="{{ $cancha->name }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex items-center justify-center h-full text-4xl">🏟️</div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-sm font-bold text-green-700">
                                S/ {{ number_format($cancha->price_per_hour, 2) }}
                            </div>
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $cancha->name }}</h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-1 rounded">
                                    📍 {{ $cancha->district->name ?? 'Cusco' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $cancha->description }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="block w-full py-2.5 bg-green-600 text-white text-center rounded-lg font-bold hover:bg-green-700 transition">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 4. SECCIÓN ¿QUIÉNES SOMOS? --}}
    <section class="py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
                ¿Quiénes somos?
            </h2>
            <div class="space-y-6">
                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                    Somos la plataforma #1 en Cusco diseñada para 
                    simplificar la vida de los deportistas. Nuestra misión es conectar la 
                    pasión por el deporte con la tecnología, ofreciendo una forma rápida y 
                    segura de reservar espacios deportivos en toda la ciudad imperial.
                </p>
                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                    Ya sea fútbol, vóley o básquet, trabajamos con 
                    los mejores complejos para garantizar que tu única preocupación sea dar 
                    lo mejor en la cancha.
                </p>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-300 pt-16 pb-8 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-4">
                    <h4 class="text-2xl font-black italic text-gray-900 dark:text-white">
                        ⚽ Pichanga<span class="text-green-500">Ya</span>
                    </h4>
                    <p class="text-sm opacity-80">Encuentra y reserva en las mejores canchas de Cusco.</p>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Explorar</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-green-500 transition">🔍 Buscar Canchas</a></li>
                        <li><a href="{{ route('register-pitch') }}" class="text-green-600 dark:text-green-400 font-bold hover:underline">🏟️ Registra tu Cancha</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-green-500 transition">¿Quiénes somos?</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Ayuda</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('suggestions.index') }}" class="hover:text-green-500 transition">Enviar Sugerencia</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-green-500 transition">Preguntas Frecuentes</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Legal</h5>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ route('terms.show') }}" class="hover:text-green-500 transition">Términos</a></li>
                        <li><a href="{{ route('policy.show') }}" class="hover:text-green-500 transition">Privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-xs opacity-60">
                &copy; {{ date('Y') }} PichangaYa Cusco.
            </div>
        </div>
    </footer>
</body>
</html>