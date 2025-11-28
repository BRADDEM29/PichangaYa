<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Distritos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes de Éxito / Error --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- COLUMNA 1: FORMULARIO DE CREAR --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold mb-4">Agregar Nuevo Distrito</h3>
                    <form action="{{ route('admin.districts.store') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nombre del Distrito</label>
                            <input type="text" name="name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" placeholder="Ej: San Blas" required>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-full">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- COLUMNA 2: LISTA DE DISTRITOS --}}
                <div class="md:col-span-2 bg-white shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Lista de Distritos</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($districts as $district)
                            <tr>
                                <td class="px-6 py-4">
                                    {{-- Formulario para EDITAR (Inline) --}}
                                    <form action="{{ route('admin.districts.update', $district->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $district->name }}" class="text-sm border-gray-300 rounded-md shadow-sm py-1 px-2 w-full">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-bold">
                                            Actualizar
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- Botón ELIMINAR --}}
                                    <form action="{{ route('admin.districts.destroy', $district->id) }}" method="POST" onsubmit="return confirm('¿Borrar este distrito?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">
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