<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Cancha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- Muestra errores si hay --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Ups!</strong>
                        <span class="block sm:inline">Revisa los campos.</span>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('owner.canchas.store') }}" method="POST">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Cancha:</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    {{-- Dirección --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    {{-- Precio --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Precio por Hora (S/.):</label>
                        <input type="number" step="0.01" name="price_per_hour" value="{{ old('price_per_hour') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    {{-- Select Deporte (sport_id) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deporte:</label>
                        <select name="sport_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Distrito (district_id) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Distrito:</label>
                        <select name="district_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Descripción (Opcional):</label>
                        <textarea name="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Guardar Cancha
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>