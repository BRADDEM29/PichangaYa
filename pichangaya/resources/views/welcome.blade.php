<x-app-layout>

    {{-- Encabezado --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bienvenido a Reserva tu Cancha') }}
        </h2>
    </x-slot>

    {{-- Contenedor Principal --}}
    <div class="py-12 relative bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 1. SECCIÓN DE BUSCADOR Y FILTROS --}}
            <div class="bg-white shadow-xl sm:rounded-2xl p-6 mb-10 border border-gray-100">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-5 gap-6">

                    {{-- Buscador de Texto --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre de la cancha o dirección..." 
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                    </div>

                    {{-- Filtro Deporte (Protegido con @isset para evitar error 500) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deporte</label>
                        <select name="sport_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                            <option value="">Todos</option>
                            @isset($sports)
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                        {{ $sport->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Filtro Distrito (Protegido con @isset) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Distrito</label>
                        <select name="district_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                            <option value="">Todos</option>
                            @isset($districts)
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Botón Filtrar --}}
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition duration-300 transform hover:-translate-y-0.5">
                            Filtrar Resultados
                        </button>
                    </div>
                </form>
            </div>

            {{-- 2. LISTADO DE RESULTADOS --}}
            @if($canchas->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-12 text-center border border-gray-100">
                    <div class="text-6xl mb-4 animate-bounce">🔍</div>
                    <p class="text-gray-500 text-lg font-medium">No se encontraron canchas con esos criterios.</p>
                    <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline mt-4 inline-block font-bold transition">
                        Ver todas las canchas
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($canchas as $cancha)
                        {{-- Tarjeta de Cancha --}}
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col h-full border border-gray-100 group">

                            {{-- Imagen de la Cancha --}}
                            <div class="h-56 bg-gray-200 w-full object-cover relative overflow-hidden">
                                @if($cancha->images && $cancha->images->count() > 0)
                                    <img src="{{ Storage::url($cancha->images->first()->url) }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                @else
                                    <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Cancha Default" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                @endif

                                {{-- Badge Precio --}}
                                <div class="absolute top-3 right-3 bg-indigo-600/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                                    S/ {{ number_format($cancha->price_per_hour, 2) }} / h
                                </div>
                            </div>

                            {{-- Información --}}
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition">{{ $cancha->name }}</h3>
                                    </div>
                                    
                                    {{-- Etiquetas seguras (Uso de ?-> para evitar errores si es null) --}}
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md border border-blue-100">
                                            ⚽ {{ $cancha->sport?->name ?? 'General' }}
                                        </span>
                                        <span class="inline-flex items-center bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-md border border-green-100">
                                            🏙️ {{ $cancha->district?->name ?? 'Sin Distrito' }}
                                        </span>
                                    </div>

                                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $cancha->description }}</p>
                                    
                                    <div class="flex items-center text-gray-500 text-xs font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $cancha->address }}
                                    </div>
                                </div>

                                {{-- Botón de Acción --}}
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <a href="{{ route('canchas.show', $cancha) }}" class="block w-full text-center bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-md">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>