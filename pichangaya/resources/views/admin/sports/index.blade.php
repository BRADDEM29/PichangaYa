<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Deportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- COLUMNA 1: FORMULARIO CREAR --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold mb-4">Agregar Nuevo Deporte</h3>
                    <form action="{{ route('admin.sports.store') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nombre</label>
                            <input type="text" name="name" class="border-gray-300 rounded-md shadow-sm block w-full mt-1" placeholder="Ej: Rugby" required>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Ícono (Emoji)</label>
                            <input type="text" name="icon" class="border-gray-300 rounded-md shadow-sm block w-full mt-1" placeholder="Ej: 🏉">
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-full">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- COLUMNA 2: LISTA --}}
                <div class="md:col-span-2 bg-white shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Lista de Deportes</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ícono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sports as $sport)
                            <tr>
                                <form action="{{ route('admin.sports.update', $sport->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <td class="px-6 py-4 w-16">
                                        <input type="text" name="icon" value="{{ $sport->icon }}" class="text-center text-xl border-gray-300 rounded-md shadow-sm w-12 p-1">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="name" value="{{ $sport->name }}" class="text-sm border-gray-300 rounded-md shadow-sm w-full p-1">
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-bold mr-2">
                                            Actualizar
                                        </button>
                                </form>
                                        {{-- BOTÓN BORRAR (Separado del form de update) --}}
                                        <form action="{{ route('admin.sports.destroy', $sport->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Borrar deporte?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-bold">
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
</x-app-layout>