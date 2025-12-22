<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\owners\courts.blade.php --}}
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Canchas de: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de Éxito --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- 🟢 ENCABEZADO CON BOTONES (VOLVER + CREAR) --}}
                <div class="flex justify-between items-center mb-6">
                    {{-- Botón Volver --}}
                    <a href="{{ route('admin.owners.index') }}" class="text-indigo-600 hover:underline font-bold text-lg flex items-center">
                        &larr; Volver a Dueños
                    </a>
                    
                    {{-- 🟢 NUEVO BOTÓN CREAR CANCHA --}}
                    <a href="{{ route('admin.owners.canchas.create', $user) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md flex items-center transition transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Crear Nueva Cancha
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distrito</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                
                                {{-- 🟢 NUEVA COLUMNA RESERVAS --}}
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Reservas</th>
                                
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Editar</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Eliminar</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acción (Destacado)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($canchas as $cancha)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $cancha->name }}</div>
                                        <div class="text-xs text-gray-500">S/ {{ $cancha->price_per_hour }}/hr</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $cancha->district->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($cancha->is_featured)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                ★ Destacada
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- 🟢 BOTÓN GESTIONAR RESERVAS --}}
                                    <td class="px-6 py-4 text-center">
                                        {{-- ⚠️ Asegúrate de tener esta ruta creada en el siguiente paso --}}
                                        <a href="{{ route('admin.canchas.reservas.index', $cancha) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-bold rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Gestionar
                                        </a>
                                    </td>

                                    {{-- Botón Editar --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.edit', $cancha) }}" class="text-blue-600 hover:text-blue-900 font-bold transition duration-150 ease-in-out">
                                            ✏️ Editar
                                        </a>
                                    </td>

                                    {{-- BOTÓN ELIMINAR --}}
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cancha? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition duration-150 ease-in-out flex items-center justify-center w-full">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Botón Destacado --}}
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.toggleFeatured', $cancha) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            @if($cancha->is_featured)
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 font-bold text-xs transition border border-yellow-600 rounded px-2 py-1">Quitar ★</button>
                                            @else
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold text-xs transition border border-green-600 rounded px-2 py-1">Poner ★</button>
                                            @endif
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