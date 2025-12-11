<x-app-layout>
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
                <a href="{{ route('admin.owners.index') }}" class="mb-4 inline-block text-indigo-600 hover:underline font-bold">&larr; Volver a Dueños</a>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distrito</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Editar</th>
                                {{-- 🟢 NUEVA COLUMNA ELIMINAR --}}
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
                                    
                                    {{-- Botón Editar --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.edit', $cancha) }}" class="text-blue-600 hover:text-blue-900 font-bold transition duration-150 ease-in-out">
                                            ✏️ Editar
                                        </a>
                                    </td>

                                    {{-- 🟢 BOTÓN ELIMINAR CON CONFIRMACIÓN --}}
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