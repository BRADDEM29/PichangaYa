<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Deportes') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <article class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <aside class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </aside>
            @endif

            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- COLUMNA 1: FORMULARIO CREAR --}}
                <article class="bg-white shadow-xl sm:rounded-lg p-6 h-fit border-t-4 border-indigo-600">
                    <header class="mb-4">
                        <h3 class="text-lg font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Nuevo Deporte
                        </h3>
                    </header>
                    
                    <form action="{{ route('admin.sports.store') }}" method="POST"
                          x-data="{ 
                              selectedIcon: 'futbol', 
                              open: false,
                              icons: {{ json_encode(config('icons.sports')) }}
                          }">
                        @csrf
                        
                        {{-- Nombre --}}
                        <fieldset class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nombre del Deporte</label>
                            <input type="text" name="name" class="border-gray-300 rounded-md shadow-sm block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej: Fútbol 7" required>
                        </fieldset>

                        {{-- 🟢 NUEVO CAMPO: Capacidad Total --}}
                        <fieldset class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Capacidad Total (Jugadores)</label>
                            <input type="number" name="total_players" min="2" max="60" class="border-gray-300 rounded-md shadow-sm block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-indigo-700" placeholder="Ej: 14" required>
                            <p class="text-xs text-gray-500 mt-1">Suma de ambos equipos (Ej: 7vs7 = 14)</p>
                        </fieldset>
                        
                        {{-- Selector de Iconos (Tu lógica original) --}}
                        <fieldset class="mt-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Seleccionar Pelota / Ícono</label>
                            <input type="hidden" name="icon" x-model="selectedIcon">

                            <div class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-3 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <span class="flex items-center">
                                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" x-html="icons[selectedIcon]">
                                        </svg>
                                        <span class="ml-3 block truncate capitalize font-bold text-gray-700" x-text="selectedIcon"></span>
                                    </span>
                                    <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </span>
                                </button>

                                <div x-show="open" style="display: none;"
                                     class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                    <div class="grid grid-cols-4 gap-2 p-2">
                                        <template x-for="(svg, name) in icons" :key="name">
                                            <div @click="selectedIcon = name; open = false" 
                                                 class="cursor-pointer p-3 rounded hover:bg-indigo-50 flex flex-col items-center justify-center border border-transparent hover:border-indigo-200 transition group">
                                                <svg class="h-8 w-8 text-gray-500 group-hover:text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" x-html="svg">
                                                </svg>
                                                <span class="text-[10px] uppercase font-bold text-gray-400 mt-1 group-hover:text-indigo-500" x-text="name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <footer class="mt-6">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Guardar Deporte
                            </button>
                        </footer>
                    </form>
                </article>

                {{-- COLUMNA 2: LISTA --}}
                <article class="md:col-span-2 bg-white shadow-xl sm:rounded-lg p-6 border-t-4 border-gray-600">
                    <header class="mb-4">
                        <h3 class="text-lg font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Lista de Deportes
                        </h3>
                    </header>
                    
                    <figure class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    {{-- 🟢 NUEVA COLUMNA --}}
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Capacidad</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sports as $sport)
                                <tr x-data="{ 
                                    editIcon: '{{ $sport->icon }}', 
                                    openDropdown: false,
                                    icons: {{ json_encode(config('icons.sports')) }}
                                }">
                                    {{-- Formulario Update Envuelve los inputs --}}
                                    <form action="{{ route('admin.sports.update', $sport->id) }}" method="POST" id="form-update-{{ $sport->id }}">
                                        @csrf
                                        @method('PUT')

                                        {{-- Selector Icono (Alpine original) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="relative">
                                                <input type="hidden" name="icon" x-model="editIcon">
                                                <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false" 
                                                        class="p-2 rounded-full hover:bg-gray-100 border border-gray-200 transition group">
                                                    <svg class="h-8 w-8 text-indigo-600 group-hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                         x-html="icons[editIcon] || icons['futbol']">
                                                    </svg>
                                                </button>

                                                <div x-show="openDropdown" style="display: none;"
                                                     class="absolute z-50 left-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 p-2 grid grid-cols-4 gap-2">
                                                    <template x-for="(svg, name) in icons" :key="name">
                                                        <div @click="editIcon = name; openDropdown = false" 
                                                             class="cursor-pointer p-1 rounded hover:bg-indigo-50 flex justify-center border border-transparent hover:border-indigo-200"
                                                             :class="editIcon === name ? 'bg-indigo-100 ring-1 ring-indigo-300' : ''">
                                                            <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" x-html="svg"></svg>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Input Nombre --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="name" value="{{ $sport->name }}" 
                                                   class="text-sm border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-700">
                                        </td>

                                        {{-- 🟢 Input Capacidad --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <input type="number" name="total_players" value="{{ $sport->total_players }}" min="2" max="60"
                                                   class="text-sm border-gray-300 rounded-md shadow-sm w-20 text-center focus:ring-indigo-500 focus:border-indigo-500 font-bold text-indigo-700 bg-indigo-50">
                                        </td>
                                    </form>
                                    
                                    {{-- Botones Acción --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="submit" form="form-update-{{ $sport->id }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 uppercase text-xs tracking-wider">
                                            Guardar
                                        </button>

                                        <form action="{{ route('admin.sports.destroy', $sport->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este deporte?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold uppercase text-xs tracking-wider">
                                                Borrar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </figure>
                </article>

            </section>
        </article>
    </section>
</x-app-layout>