<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Script de estado de usuario --}}
    <script>
        window.usuarioEstaLogueado = {{ auth()->check() ? 'true' : 'false' }};
    </script>

    <title>PichangaYa - Reserva Canchas de Fútbol y Vóley en Cusco</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        html { scroll-behavior: smooth; }
        .text-shadow-strong { text-shadow: 0 4px 10px rgba(0, 0, 0, 0.9); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">

    @include('navigation-menu')

    {{-- 1. HERO SECTION --}}
    <div class="relative bg-gray-900 text-white overflow-hidden pb-10">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-900/40 to-gray-900/60"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 text-shadow-strong">
                Juega sin límites en <span class="text-yellow-400">Cusco</span>
            </h1>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-medium">
                Reserva canchas de fútbol, vóley y más al instante.
            </p>
            
            <div class="bg-white p-3 rounded-xl shadow-2xl max-w-4xl mx-auto backdrop-blur-sm bg-white/95">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="¿Nombre de la cancha?" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 h-12">
                    
                    <select name="district_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 h-12">
                        <option value="">Todo Cusco</option>
                        @isset($districts)
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                    
                    <select name="sport_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 h-12">
                        <option value="">Deporte</option>
                        @isset($sports)
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                    
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition shadow-md h-12 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 🟢 2. CARRUSEL DE LAS MEJORES CANCHAS (ARREGLADO) --}}
    @if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request()->has('search') && !request()->has('sport_id'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10 mb-16">
        
        <div class="flex items-center gap-3 mb-6">
            <span class="text-3xl">⭐</span>
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-wide">Las Mejores Canchas</h3>
        </div>
        
        {{-- CONTENEDOR CARRUSEL --}}
        <div x-data="{ 
                activeSlide: 0, 
                total: {{ $featuredCanchas->count() }}, 
                interval: null,
                init() {
                    this.start();
                },
                start() {
                    if(this.interval) clearInterval(this.interval);
                    this.interval = setInterval(() => this.next(), 5000); // 5 Segundos
                },
                stop() {
                    clearInterval(this.interval);
                },
                next() {
                    this.activeSlide = (this.activeSlide + 1) % this.total;
                },
                prev() {
                    this.activeSlide = (this.activeSlide - 1 + this.total) % this.total;
                },
                manualNext() {
                    this.stop();
                    this.next();
                    this.start();
                },
                manualPrev() {
                    this.stop();
                    this.prev();
                    this.start();
                }
             }" 
             @mouseenter="stop()" 
             @mouseleave="start()"
             class="relative w-full h-[550px] bg-black rounded-3xl shadow-2xl overflow-hidden group border-4 border-white">

            {{-- PISTA DESLIZANTE --}}
            <div class="flex h-full transition-transform duration-700 ease-out will-change-transform"
                 :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                
                @foreach($featuredCanchas as $fc)
                    <div class="min-w-full h-full relative">
                        <a href="{{ route('canchas.show', $fc) }}" class="absolute inset-0 z-30 w-full h-full cursor-pointer"></a>

                        {{-- IMAGEN --}}
                        @if($fc->getFirstMediaUrl('canchas'))
                            <img src="{{ $fc->getFirstMediaUrl('canchas', 'large') }}" class="absolute inset-0 w-full h-full object-cover brightness-[0.45] transition-transform duration-[10000ms] ease-linear group-hover:scale-110">
                        @else
                            <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" class="absolute inset-0 w-full h-full object-cover brightness-[0.45]">
                        @endif

                        {{-- TEXTO --}}
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center z-20 px-6 pointer-events-none">
                            <span class="inline-block bg-yellow-400 text-black text-xs font-black px-4 py-1.5 rounded-full mb-6 uppercase tracking-widest shadow-lg transform -skew-x-12">
                                ★ Destacada
                            </span>
                            <h2 class="text-5xl md:text-7xl font-black text-white mb-4 drop-shadow-xl text-shadow-strong leading-tight">
                                {{ $fc->name }}
                            </h2>
                            <p class="text-gray-200 text-lg md:text-xl mb-8 font-medium max-w-3xl drop-shadow-md line-clamp-2">
                                {{ $fc->description ?? 'Una de las mejores opciones deportivas en ' . ($fc->district->name ?? 'Cusco') . '. ¡Reserva tu horario ahora!' }}
                            </p>
                            <div class="bg-indigo-600 text-white text-lg font-bold py-4 px-10 rounded-full shadow-2xl border-2 border-white/20 flex items-center gap-2 group-hover:bg-indigo-500 transition-colors">
                                <span>Reservar Ahora</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- BOTONES FLECHAS (Z-INDEX ALTO) --}}
            <button @click.prevent="manualPrev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 text-white p-3 rounded-full backdrop-blur-md transition z-50 cursor-pointer opacity-0 group-hover:opacity-100 border border-white/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click.prevent="manualNext()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 text-white p-3 rounded-full backdrop-blur-md transition z-50 cursor-pointer opacity-0 group-hover:opacity-100 border border-white/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- PUNTOS INDICADORES --}}
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-40">
                <template x-for="i in total">
                    <button @click="stop(); activeSlide = i - 1; start();" 
                            :class="{'bg-yellow-400 w-10': activeSlide === i - 1, 'bg-white/40 w-3 hover:bg-white': activeSlide !== i - 1}"
                            class="h-3 rounded-full transition-all duration-300 shadow-sm cursor-pointer"></button>
                </template>
            </div>

        </div>
    </div>
    @endif

    {{-- 3. RESULTADOS CANCHAS GENERALES --}}
    <div id="seccion-canchas" class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 border-l-4 border-indigo-600 pl-4">Explora Todas las Canchas</h2>

            @if($canchas->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-7xl mb-4">🏟️</div>
                    <h3 class="text-2xl font-bold text-gray-900">No encontramos resultados</h3>
                    <p class="text-gray-500 mt-2">Intenta cambiar los filtros de búsqueda o el distrito.</p>
                    <a href="{{ route('home') }}" class="mt-6 inline-block bg-indigo-50 text-indigo-700 px-6 py-2 rounded-full font-bold hover:bg-indigo-100 transition">Limpiar Filtros</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($canchas as $cancha)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-full transform hover:-translate-y-1">
                            <div class="h-56 w-full bg-gray-200 relative overflow-hidden">
                                @if($cancha->getFirstMediaUrl('canchas'))
                                    <img src="{{ $cancha->getFirstMediaUrl('canchas', 'large') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="flex items-center justify-center h-full text-4xl bg-gray-100 text-gray-400 flex-col">
                                        <span>📷</span>
                                        <span class="text-xs mt-2">Sin Foto</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg shadow-sm font-bold text-indigo-700 text-sm border border-gray-100">
                                    S/ {{ $cancha->price_per_hour }} <span class="text-xs font-normal text-gray-500">/h</span>
                                </div>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="mb-3">
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 group-hover:text-indigo-600 transition-colors">{{ $cancha->name }}</h3>
                                        <div class="flex items-center text-sm text-gray-500 font-medium">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $cancha->district->name ?? 'Cusco' }}
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($cancha->sports && $cancha->sports->count() > 0)
                                            @foreach($cancha->sports->take(2) as $sport) 
                                                <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md border border-blue-100 uppercase tracking-wide">{{ $sport->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-md border border-gray-200">DEPORTE GENERAL</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-t border-gray-100 pt-4 mt-2">
                                    <a href="{{ route('canchas.show', $cancha) }}" class="block w-full py-3 bg-gray-900 text-white text-center rounded-xl font-bold hover:bg-indigo-600 transition shadow-md group-hover:shadow-lg">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-bold text-2xl mb-2">⚽ Pichanga<span class="text-yellow-500">Ya</span></p>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Todos los derechos reservados. Cusco, Perú.</p>
        </div>
    </footer>
</body>
</html>