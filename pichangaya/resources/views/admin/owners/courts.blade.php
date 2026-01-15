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
                    <a href="{{ route('admin.owners.index') }}" class="text-indigo-600 hover:underline font-bold text-lg flex items-center gap-2">
                        {{-- Icono Arrow Left --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver a Dueños
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
                                            <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 gap-1">
                                                {{-- Icono Star Solid --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-yellow-600">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                                </svg>
                                                Destacada
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- 🟢 BOTÓN GESTIONAR RESERVAS --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.reservas.index', $cancha) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-bold rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Gestionar
                                        </a>
                                    </td>

                                    {{-- Botón Editar --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.edit', $cancha) }}" class="text-blue-600 hover:text-blue-900 font-bold transition duration-150 ease-in-out flex items-center justify-center gap-1">
                                            {{-- Icono Pencil --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                            Editar
                                        </a>
                                    </td>

                                    {{-- BOTÓN ELIMINAR --}}
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cancha? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition duration-150 ease-in-out flex items-center justify-center w-full gap-1">
                                                {{-- Icono Trash --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Botón Destacado --}}
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.toggleFeatured', $cancha) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            @if($cancha->is_featured)
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 font-bold text-xs transition border border-yellow-600 rounded px-2 py-1 flex items-center justify-center gap-1 mx-auto">
                                                    Quitar 
                                                    {{-- Icono Star Slash --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold text-xs transition border border-green-600 rounded px-2 py-1 flex items-center justify-center gap-1 mx-auto">
                                                    Poner 
                                                    {{-- Icono Star --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                    </svg>
                                                </button>
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