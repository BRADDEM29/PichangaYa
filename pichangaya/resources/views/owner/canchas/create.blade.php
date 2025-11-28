<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Cancha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- 1. Definimos la ruta de destino (store) --}}
                    <form action="{{ route('owner.canchas.store') }}" method="POST">
                        @csrf {{-- 2. Token de seguridad OBLIGATORIO --}}

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        {{-- Dirección --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        {{-- Select de Distritos --}}
                        <div class="mb-4">
                            <label for="district_id" class="block text-sm font-medium text-gray-700">Distrito</label>
                            <select name="district_id" id="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Selecciona un distrito</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select de Deportes --}}
                        <div class="mb-4">
                            <label for="sport_id" class="block text-sm font-medium text-gray-700">Deporte</label>
                            <select name="sport_id" id="sport_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Selecciona un deporte</option>
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-700">Precio por Hora</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Cancha
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>