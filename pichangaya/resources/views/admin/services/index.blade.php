<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\services\index.blade.php --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Servicios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- COLUMNA 1: FORMULARIO CREAR --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 h-fit border-t-4 border-indigo-600">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Nuevo Servicio
                    </h3>
                    
                    <form action="{{ route('admin.services.store') }}" method="POST"
                          {{-- 🟢 AQUÍ EL PRIMER CAMBIO: icons.services --}}
                          x-data="{ 
                              selectedIcon: 'pasto_sintetico', 
                              open: false,
                              icons: {{ json_encode(config('icons.services')) }}
                          }">
                        @csrf
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nombre del Servicio</label>
                            <input type="text" name="name" class="border-gray-300 rounded-md shadow-sm block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej: Cancha Sintética" required>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Ícono</label>
                            
                            {{-- Input oculto --}}
                            <input type="hidden" name="icon" x-model="selectedIcon">

                            {{-- Selector Visual --}}
                            <div class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-3 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <span class="flex items-center">
                                        {{-- SVG principal --}}
                                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" x-html="icons[selectedIcon]">
                                        </svg>
                                        <span class="ml-3 block truncate capitalize font-bold text-gray-700" x-text="selectedIcon.replace(/_/g, ' ')"></span>
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
                                                 class="cursor-pointer p-2 rounded hover:bg-indigo-50 flex flex-col items-center justify-center border border-transparent hover:border-indigo-200 transition group">
                                                <svg class="h-6 w-6 text-gray-500 group-hover:text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" x-html="svg"></svg>
                                                {{-- Muestra el nombre reemplazando guiones bajos por espacios --}}
                                                <span class="text-[9px] uppercase font-bold text-gray-400 mt-1 group-hover:text-indigo-600 text-center leading-tight" x-text="name.replace(/_/g, ' ')"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>

                {{-- COLUMNA 2: LISTA --}}
                <div class="md:col-span-2 bg-white shadow-xl sm:rounded-lg p-6 border-t-4 border-gray-600">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Lista de Servicios
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ícono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($services as $service)
                                <tr x-data="{ 
                                    editIcon: '{{ $service->icon }}', 
                                    openDropdown: false,
                                    {{-- 🟢 AQUÍ EL SEGUNDO CAMBIO: icons.services --}}
                                    icons: {{ json_encode(config('icons.services')) }}
                                }">
                                    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" id="form-update-{{ $service->id }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <td class="px-6 py-4 w-16 whitespace-nowrap">
                                            <div class="relative">
                                                <input type="hidden" name="icon" x-model="editIcon">
                                                <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false" 
                                                        class="p-2 rounded-full hover:bg-gray-100 border border-gray-200 transition group">
                                                    {{-- Muestra el ícono actual o pasto_sintetico por defecto --}}
                                                    <svg class="h-8 w-8 text-indigo-600 group-hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" 
                                                         x-html="icons[editIcon] || icons['pasto_sintetico']">
                                                    </svg>
                                                </button>

                                                {{-- Dropdown Flotante --}}
                                                <div x-show="openDropdown" style="display: none;"
                                                     class="absolute z-50 left-0 mt-2 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 p-2 grid grid-cols-5 gap-2">
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
                                        
                                        <td class="px-6 py-4">
                                            <input type="text" name="name" value="{{ $service->name }}" class="text-sm border-gray-300 rounded-md shadow-sm w-full p-1 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-gray-700">
                                        </td>
                                    </form>

                                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                        <button type="submit" form="form-update-{{ $service->id }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 uppercase text-xs tracking-wider">
                                            Actualizar
                                        </button>
                                        
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de borrar este servicio?');">
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
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>