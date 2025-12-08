<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- 📍 TOUR: ID DEL CONTENEDOR PRINCIPAL --}}
    <div class="py-12" id="tour-contenido"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SECCIÓN 1: BUSCADOR Y FILTROS (DISEÑO RECTO) --}}
            {{-- 🟢 MEJORA: Este bloque ahora es independiente y tiene mb-8 para separarse de los resultados --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8">
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    
                    {{-- 1. Buscador de Texto (Se estira para llenar espacio) --}}
                    <div class="w-full md:flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                            Buscar
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Nombre de la cancha..." 
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                        </div>
                    </div>

                    {{-- 2. Filtro Deporte --}}
                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                            Deporte
                        </label>
                        <select name="sport_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                            <option value="">Todos los deportes</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Filtro Distrito --}}
                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                            Distrito
                        </label>
                        <select name="district_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                            <option value="">Todos los distritos</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Botón de Filtrar (Alineado) --}}
                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition h-10">
                            🔍 Buscar 
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN 2: RESULTADOS (GRILLA) --}}
            {{-- 🟢 MEJORA: Contenedor separado para los resultados --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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
                                        
                                        {{-- Botón Ver Detalles --}}
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