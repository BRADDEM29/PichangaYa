<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PichangaYa - Reserva Canchas en Cusco</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">

    @include('navigation-menu')

    {{-- 1. HERO SECTION (BUSCADOR) --}}
    <div class="relative bg-gray-900 text-white overflow-hidden pb-16"> {{-- Aumenté el padding bottom --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-900/40 to-gray-900/60"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 drop-shadow-lg">
                Juega sin límites en <span class="text-yellow-400">Cusco</span>
            </h1>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-medium">
                Reserva canchas de fútbol, vóley y más al instante.
            </p>
            
            {{-- BUSCADOR --}}
            <div class="bg-white p-4 rounded-xl shadow-2xl max-w-4xl mx-auto text-gray-900 relative z-20"> {{-- Z-20 para asegurar que esté encima --}}
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="¿Nombre de la cancha?" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-12">
                    
                    <select name="district_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-12">
                        <option value="">Todo Cusco</option>
                        @isset($districts)
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                    
                    <select name="sport_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-12">
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

    {{-- 2. CARRUSEL (SEPARADO) --}}
    {{-- Quitamos los márgenes negativos (-mt) para que no tape al buscador --}}
    @if(isset($featuredCanchas) && $featuredCanchas->isNotEmpty() && !request()->has('search') && !request()->has('sport_id'))
        <div class="bg-white py-12"> {{-- Fondo blanco y separación --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">⭐</span>
                   <h3 class="text-2xl font-black text-gray-900 uppercase tracking-wide">Mejores Canchas</h3>
                </div>
                
                {{-- Llamada al componente --}}
                <x-carousel :items="$featuredCanchas" />
            </div>
        </div>
    @endif

    {{-- 3. RESULTADOS --}}
    <div id="seccion-canchas" class="py-12 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 border-l-4 border-indigo-600 pl-4">Explora Todas las Canchas</h2>

            @if($canchas->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="text-7xl mb-4">🏟️</div>
                    <h3 class="text-2xl font-bold text-gray-900">No encontramos resultados</h3>
                    <a href="{{ route('home') }}" class="mt-6 inline-block bg-indigo-50 text-indigo-700 px-6 py-2 rounded-full font-bold hover:bg-indigo-100 transition">Limpiar Filtros</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($canchas as $cancha)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-full transform hover:-translate-y-1">
                            <div class="h-56 w-full bg-gray-200 relative overflow-hidden">
                                @if($cancha->getFirstMediaUrl('canchas'))
                                    <img src="{{ $cancha->getFirstMediaUrl('canchas', 'large') }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                                @else
                                    <div class="flex items-center justify-center h-full text-4xl bg-gray-100 text-gray-400 flex-col">
                                        <span>📷</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg shadow-sm font-bold text-indigo-700 text-sm">
                                    S/ {{ number_format($cancha->price_per_hour, 2) }}
                                </div>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $cancha->name }}</h3>
                                    <p class="text-sm text-gray-500 mb-4">{{ $cancha->district->name ?? 'Cusco' }}</p>
                                </div>
                                <div class="border-t border-gray-100 pt-4">
                                    <a href="{{ route('canchas.show', $cancha) }}" class="block w-full py-3 bg-gray-900 text-white text-center rounded-xl font-bold hover:bg-indigo-600 transition">
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

    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-bold text-2xl mb-2">⚽ Pichanga<span class="text-yellow-500">Ya</span></p>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Cusco, Perú.</p>
        </div>
    </footer>
</body>
</html>