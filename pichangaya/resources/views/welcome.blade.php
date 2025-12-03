<x-app-layout>

    {{-- Header Opcional --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bienvenido a Reserva tu Cancha') }}
        </h2>
    </x-slot>

    <div class="py-12 relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SECCIÓN 1: BUSCADOR Y FILTROS --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                    {{-- Campo: Buscador --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o dirección..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Campo: Filtro Deporte --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deporte</label>
                        <select name="sport_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Campo: Filtro Distrito --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Distrito</label>
                        <select name="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botón: Filtrar --}}
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN 2: LISTADO DE CANCHAS --}}
            @if($canchas->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500 text-lg">No se encontraron canchas con esos criterios.</p>
                    <a href="{{ route('home') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Ver todas las canchas</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($canchas as $cancha)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col h-full">

                            {{-- Imagen --}}
                            <div class="h-48 bg-gray-200 w-full object-cover relative">
                                @if($cancha->images && $cancha->images->count() > 0)
                                    <img src="{{ Storage::url($cancha->images->first()->url) }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Cancha Default" class="w-full h-full object-cover">
                                @endif
                                <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs font-bold px-2 py-1 m-2 rounded">
                                    S/ {{ $cancha->price_per_hour }}/h
                                </div>
                            </div>

                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $cancha->name }}</h3>
                                    </div>
                                    <span class="inline-block mt-2 bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        {{ $cancha->district->name }} • {{ $cancha->sport->name }}
                                    </span>
                                    <p class="text-gray-600 mt-2 text-sm line-clamp-2">{{ $cancha->description }}</p>
                                    <p class="text-gray-500 text-xs mt-1">📍 {{ $cancha->address }}</p>
                                </div>

                                {{-- SECCIÓN DE BOTONES --}}
                                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col gap-2">
                                    
                                    @auth
                                        {{-- 1. USUARIO LOGUEADO (Ve ambos botones y funcionan normal) --}}
                                        <a href="{{ route('canchas.show', $cancha) }}" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition">
                                            Ver detalles
                                        </a>
                                        <a href="{{ route('reservas.create', $cancha) }}" class="block w-full text-center bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                                            Registrar Reserva
                                        </a>
                                    @else
                                        {{-- 2. INVITADO (Solo ve botón Ver Detalles que lanza alerta) --}}
                                        {{-- Se eliminó el botón de Registrar Reserva aquí --}}
                                        <button x-data @click="$dispatch('guest-alert')" type="button" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition">
                                            Ver detalles
                                        </button>
                                    @endauth

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ALERTA FLOTANTE CON REDIRECCIÓN --}}
    {{-- Configurada para redirigir a Register después de 2500ms (2.5 segundos) --}}
    <div x-data="{ show: false }" 
         x-on:guest-alert.window="show = true; setTimeout(() => window.location.href = '{{ route('register') }}', 2500)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-8"
         class="fixed top-20 right-5 z-50 bg-red-600 text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 border border-red-700"
         style="display: none;">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>

        <div>
            <h4 class="font-bold text-lg">Acceso Restringido</h4>
            <p class="text-sm font-medium">Debes registrarte o iniciar sesión para registrarte.</p>
            <p class="text-xs mt-1 text-red-200">Redirigiendo...</p>
        </div>
    </div>

</x-app-layout>