<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserva tu Cancha - Inicio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- BARRA DE NAVEGACIÓN PÚBLICA --}}
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                {{-- Logo y Enlace Home --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 hover:text-indigo-800 transition">
                        ⚽ Cancha Fácil
                    </a>
                </div>

                {{-- Menú de Navegación (Condicional) --}}
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth
                        {{-- Si está logueado, lo mandamos al dashboard --}}
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 underline bg-gray-50 px-3 py-2 rounded hover:bg-gray-100 transition font-medium">Ir al Dashboard</a>
                    @else
                        {{-- Si NO está logueado, botones de Login/Registro --}}
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline px-3 py-2 rounded hover:bg-gray-50 transition font-medium">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-white bg-indigo-600 px-4 py-2 rounded-md hover:bg-indigo-700 font-bold transition shadow-md">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>

                {{-- Botón de Menú Móvil (Simple) --}}
                <div class="-me-2 flex items-center sm:hidden">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline px-2 py-1 transition font-medium">Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 underline px-2 py-1 transition font-medium">Dashboard</a>
                    @endguest
                </div>

            </div>
        </div>
    </nav>
    {{-- FIN BARRA DE NAVEGACIÓN PÚBLICA --}}


    {{-- CONTENIDO DEL DASHBOARD --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-800">Encuentra y Reserva tu Cancha Ideal</h1>
                <p class="text-gray-600 mt-2">Explora las mejores opciones deportivas en tu ciudad</p>
            </div>

            {{-- SECCIÓN 1: BUSCADOR Y FILTROS --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8">
                {{-- La acción apunta a la ruta 'home' (/) para filtrar en la misma página pública --}}
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    {{-- Buscador de Texto --}}
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nombre o dirección..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Filtro Deporte --}}
                    <div>
                        <label for="sport_id" class="block text-sm font-medium text-gray-700">Deporte</label>
                        <select id="sport_id" name="sport_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro Distrito --}}
                    <div>
                        <label for="district_id" class="block text-sm font-medium text-gray-700">Distrito</label>
                        <select id="district_id" name="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botón Buscar --}}
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold shadow-md transition">
                            🔍 Buscar
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN 2: RESULTADOS (GRILLA) --}}
            @if($canchas->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 text-lg">No encontramos canchas con esos filtros. 😢</p>
                    <a href="{{ route('home') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Ver todas las canchas</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($canchas as $cancha)
                        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition duration-300 flex flex-col h-full">
                            
                            {{-- FOTO Y PRECIO --}}
                            <div class="h-48 w-full bg-gray-200 relative">
                                {{-- Usando el helper de Spatie Media Library --}}
                                @if($cancha->getFirstMediaUrl('canchas'))
                                    <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-4xl bg-gray-300 text-gray-500 font-semibold">
                                        FOTO NO DISPONIBLE
                                    </div>
                                @endif
                                
                                <div class="absolute top-3 right-3 bg-indigo-600 text-white px-3 py-1 rounded-full shadow-lg text-lg font-extrabold">
                                    S/ {{ $cancha->price_per_hour }} <span class="text-sm font-normal">/ hr</span>
                                </div>
                            </div>
                            
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-snug">{{ $cancha->name }}</h3>

                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            📍 {{ $cancha->district->name }}
                                        </span>
                                        @foreach($cancha->sports->take(3) as $sport) 
                                            <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2 py-1 rounded-full border border-indigo-100">
                                                {{ $sport->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <p class="text-gray-600 mt-3 text-sm line-clamp-2">{{ $cancha->description }}</p>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    
                                    {{-- BOTÓN CONDICIONAL --}}
                                    @auth
                                        {{-- Si está logueado, va al detalle para reservar --}}
                                        <a href="{{ route('canchas.show', $cancha) }}" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition transform hover:scale-[1.01] shadow-md">
                                            Ver detalles y reservar &rarr;
                                        </a>
                                    @else
                                        {{-- Si NO está logueado, lo manda a registrarse --}}
                                        <a href="{{ route('register') }}" class="block w-full text-center bg-gray-800 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-900 transition transform hover:scale-[1.01] shadow-md">
                                            Regístrate para reservar
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
</body>
</html>