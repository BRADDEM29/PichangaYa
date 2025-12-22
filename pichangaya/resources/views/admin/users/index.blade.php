<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes Flash --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- ENCABEZADO: Título y Botón Crear --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg">👥</span>
                        Lista de Usuarios Registrados
                    </h3>
                    
                    {{-- 🟢 BOTÓN CREAR USUARIO --}}
                    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Crear Nuevo Usuario
                    </a>
                </div>

                {{-- TABLA --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Email / Celular</th>
                                <th class="px-6 py-3 text-center">Rol Actual</th>
                                <th class="px-6 py-3 text-center">Estado</th> {{-- 🟢 Columna Nueva --}}
                                <th class="px-6 py-3 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 font-mono text-xs">{{ $user->id }}</td>
                                    
                                    {{-- Nombre y Avatar --}}
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                        @endif
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    </td>

                                    {{-- Contacto --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                                        <div class="text-xs text-indigo-500 font-bold">{{ $user->phone ?? 'Sin celular' }}</div>
                                    </td>

                                    {{-- Rol --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded border border-red-200">Admin</span>
                                        @elseif($user->role === 'owner')
                                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-0.5 rounded border border-purple-200">Dueño</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200">Cliente</span>
                                        @endif
                                    </td>

                                    {{-- 🟢 Estado (Bloqueo/Strikes) --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_blocked)
                                            <span class="bg-black text-white text-xs font-black px-2 py-1 rounded uppercase tracking-wider">BLOQUEADO</span>
                                        @else
                                            @if($user->consecutive_cancellations > 0)
                                                <span class="text-orange-600 font-bold text-xs">⚠️ {{ $user->consecutive_cancellations }} Strikes</span>
                                            @else
                                                <span class="text-green-600 font-bold text-xs">✅ Limpio</span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            
                                            {{-- Botón Editar --}}
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-100 rounded">
                                                ✏️
                                            </a>

                                            {{-- 🟢 BOTÓN BLOQUEAR/DESBLOQUEAR (Formulario) --}}
                                            @if($user->id !== auth()->id()) {{-- No te puedes bloquear a ti mismo --}}
                                                <form action="{{ route('admin.users.toggleBlock', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cambiar el bloqueo de este usuario?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-xs font-bold uppercase px-3 py-1 rounded transition border
                                                        {{ $user->is_blocked 
                                                            ? 'bg-green-100 text-green-700 border-green-300 hover:bg-green-200' 
                                                            : 'bg-red-100 text-red-700 border-red-300 hover:bg-red-200' }}">
                                                        {{ $user->is_blocked ? '🔓 Desbloquear' : '🔒 Bloquear' }}
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>