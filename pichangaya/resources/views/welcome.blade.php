<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 🟢 LA CLAVE MAESTRA: Variable Global JS para el Tour --}}
    <script>
        window.usuarioEstaLogueado = {{ auth()->check() ? 'true' : 'false' }};
    </script>

    {{-- ============================================================== --}}
    {{-- 🚀 SEO OPTIMIZATION --}}
    {{-- ============================================================== --}}
    <title>PichangaYa - Reserva Canchas de Fútbol y Vóley en Cusco</title>
    <meta name="description" content="La plataforma #1 en Cusco para reservar canchas deportivas. Encuentra losas de fútbol, vóley y básquet, compara precios y reserva al instante sin llamadas.">
    <meta name="keywords" content="canchas cusco, pichanga cusco, alquiler losas deportivas, reserva futbol, voley cusco, wanchaq deportes">
    <meta name="author" content="PichangaYa">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="PichangaYa - Tu partido empieza con un clic">
    <meta property="og:description" content="Reserva canchas deportivas en Cusco fácil y rápido. Mira fotos, horarios y precios reales.">
    <meta property="og:image" content="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1000&auto=format&fit=crop">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Scroll suave para el botón del Hero --}}
    <style>html { scroll-behavior: smooth; }</style>
</head>
<body class="font-sans antialiased bg-gray-50">

    {{-- 1. NAVBAR --}}
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-black text-indigo-700 hover:text-indigo-800 transition tracking-tighter">
                        ⚽ Pichanga<span class="text-yellow-500">Ya</span>
                    </a>
                </div>

                {{-- Menú --}}
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full hover:bg-indigo-100 transition">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 px-3 py-2 transition">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-white bg-indigo-600 px-5 py-2 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-0.5">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- 2. HERO SECTION --}}
    <div class="relative bg-gray-900 text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-900/40 to-gray-900/60"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">
                Juega sin límites en <span class="text-yellow-400">Cusco</span>
            </h1>
            <p class="text-xl text-gray-300 mb-6 max-w-2xl mx-auto">
                La forma más rápida de encontrar y reservar canchas de fútbol, vóley y más. Sin llamadas, sin esperas.
            </p>

            {{-- 👉 ID="TOUR-RESERVAS" --}}
            <div class="mb-10">
                <a href="#seccion-canchas" id="tour-reservas" class="inline-block bg-yellow-400 text-gray-900 font-black text-lg px-8 py-3 rounded-full hover:bg-yellow-300 transition shadow-lg transform hover:scale-105">
                    ¡Reserva tu Cancha Ahora!
                </a>
            </div>
            
            {{-- BUSCADOR --}}
            <div class="bg-white p-2 rounded-lg shadow-2xl max-w-3xl mx-auto transform translate-y-6">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="¿Nombre de la cancha?" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                    
                    <select name="district_id" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                        <option value="">Todo Cusco</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>

                    <select name="sport_id" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                        <option value="">Deporte</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                        🔍 Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. RESULTADOS --}}
    <div id="seccion-canchas" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-8 px-4 sm:px-0">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Canchas Disponibles</h2>
                    <p class="text-gray-500 mt-1">Explora las mejores opciones cerca de ti.</p>
                </div>
            </div>

            @if($canchas->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <div class="text-6xl mb-4">😢</div>
                    <h3 class="text-xl font-bold text-gray-900">No encontramos resultados</h3>
                    <p class="text-gray-500">Intenta cambiar los filtros de búsqueda.</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Ver todo</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($canchas as $cancha)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group">
                            
                            {{-- FOTO --}}
                            <div class="h-56 w-full bg-gray-200 relative overflow-hidden">
                                @if($cancha->getFirstMediaUrl('canchas'))
                                    <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="flex items-center justify-center h-full text-4xl bg-gray-100 text-gray-400">🏟️</div>
                                @endif
                                
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm font-bold text-indigo-700 text-sm">
                                    S/ {{ $cancha->price_per_hour }}
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight mb-1">{{ $cancha->name }}</h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $cancha->district->name }}
                                    </div>
                                </div>

                                {{-- TAGS --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($cancha->sports->take(3) as $sport) 
                                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-100">
                                            {{ $sport->icon }} {{ $sport->name }}
                                        </span>
                                    @endforeach
                                    @if($cancha->sports->count() > 3)
                                        <span class="text-xs text-gray-400 font-semibold flex items-center">+{{ $cancha->sports->count() - 3 }}</span>
                                    @endif
                                </div>

                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    @auth
                                        {{-- 👉 ID="TOUR-DETALLES" (Solo en la primera tarjeta) --}}
                                        <a href="{{ route('canchas.show', $cancha) }}" 
                                           class="block w-full py-3 bg-gray-900 text-white text-center rounded-xl font-bold hover:bg-indigo-600 transition shadow-md"
                                           @if($loop->first) id="tour-detalles" @endif>
                                            Ver Detalles
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" class="block w-full py-3 bg-indigo-600 text-white text-center rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                                            Regístrate para Reservar
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- 4. SEO CONTENT --}}
    <div class="bg-white py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8">¿Por qué elegir PichangaYa?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-lg font-bold mb-2">Rápido y Fácil</h3>
                    <p class="text-gray-500">Reserva en menos de 1 minuto desde tu celular o computadora.</p>
                </div>
                <div class="p-6">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-lg font-bold mb-2">Ubicación Exacta</h3>
                    <p class="text-gray-500">Mapas integrados para que tú y tu equipo lleguen sin problemas.</p>
                </div>
                <div class="p-6">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-lg font-bold mb-2">Contacto Directo</h3>
                    <p class="text-gray-500">Comunícate con el dueño vía WhatsApp con un solo clic.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-bold text-lg">⚽ Pichanga<span class="text-yellow-500">Ya</span></p>
            <p class="text-gray-400 text-sm mt-2">&copy; {{ date('Y') }} Todos los derechos reservados. Cusco, Perú.</p>
        </div>
    </footer>

</body>
</html>