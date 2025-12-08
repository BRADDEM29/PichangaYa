<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- 📍 TOUR: ID DEL CONTENEDOR PRINCIPAL --}}
    <div class="py-12" id="tour-contenido"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                {{-- SECCIÓN 1: BUSCADOR (Ahora dentro del contenedor blanco) --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        
                        {{-- Buscador de Texto --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o dirección..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Filtro Deporte --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deporte</label>
                            <select name="sport_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los deportes</option>
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                        {{ $sport->icon }} {{ $sport->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro Distrito --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Distrito</label>
                            <select name="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los distritos</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botón Buscar --}}
                        <div class="flex items-end md:col-start-4">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold shadow-md transition">
                                🔍 Buscar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SECCIÓN 2: RESULTADOS (GRILLA) --}}
                <div class="p-6">
                    @if($canchas->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-500 text-lg">No encontramos canchas con esos filtros. 😢</p>
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline">Limpiar filtros</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($canchas as $cancha)
                                <div class="bg-white border border-gray-100 overflow-hidden shadow-lg rounded-lg hover:shadow-2xl transition duration-300 flex flex-col h-full">
                                    
                                    {{-- FOTO --}}
                                    <div class="h-48 w-full bg-gray-200 relative">
                                        @if($cancha->getFirstMediaUrl('canchas'))
                                            <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-4xl bg-gray-100">🏟️</div>
                                        @endif
                                        
                                        <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded shadow text-sm font-bold text-gray-800">
                                            S/ {{ $cancha->price_per_hour }}
                                        </div>
                                    </div>
                                    
                                    <div class="p-5 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $cancha->name }}</h3>
                                            </div>

                                            {{-- ETIQUETAS: Distrito y Deportes --}}
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded">
                                                    📍 {{ $cancha->district->name }}
                                                </span>

                                                @foreach($cancha->sports->take(3) as $sport) 
                                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2 py-1 rounded border border-indigo-200">
                                                        {{ $sport->icon }} {{ $sport->name }}
                                                    </span>
                                                @endforeach
                                                
                                                @if($cancha->sports->count() > 3)
                                                    <span class="text-xs text-gray-500 flex items-center font-semibold ml-1">
                                                        +{{ $cancha->sports->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-gray-600 mt-2 text-sm line-clamp-2">{{ $cancha->description }}</p>
                                            <p class="text-gray-500 text-xs mt-1">📍 {{ $cancha->address }}</p>
                                        </div>
                                        
                                        {{-- 🟢 AQUÍ ESTÁ EL CAMBIO SOLICITADO --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            {{-- 📍 TOUR: ID PARA EL BOTÓN VER DETALLES --}}
                                            <a href="{{ route('canchas.show', $cancha) }}" 
                                               class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition"
                                               id="tour-detalles"> 
                                                Ver detalles &rarr;
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>