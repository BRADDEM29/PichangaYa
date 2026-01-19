<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            🏆 Crear Nuevo Torneo (Copa Vector Pro)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <form action="{{ route('admin.tournaments.store') }}" method="POST">
                    @csrf
                    
                    {{-- Datos Generales --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nombre del Torneo</label>
                            <input type="text" name="name" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="Ej: Copa Verano 2024" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Fecha de Inicio</label>
                            <input type="date" name="start_date" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700">Premio / Descripción</label>
                            <input type="text" name="prize_description" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="Ej: S/ 1000 + Trofeo">
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    {{-- Ingreso de Equipos --}}
                    <h3 class="text-lg font-bold text-indigo-600 mb-4">Registrar 8 Equipos (Cuartos de Final)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="flex items-center">
                                <span class="bg-gray-200 text-gray-700 font-bold px-3 py-2 rounded-l-md border border-r-0 border-gray-300">
                                    {{ $i }}
                                </span>
                                <input type="text" name="teams[]" class="w-full border-gray-300 rounded-r-md shadow-sm" placeholder="Nombre Equipo {{ $i }}" required>
                            </div>
                        @endfor
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                            🚀 Generar Bracket y Publicar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>