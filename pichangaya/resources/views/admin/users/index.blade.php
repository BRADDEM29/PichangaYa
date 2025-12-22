<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\users\index.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes Flash --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded shadow-sm flex items-center gap-2">
                    <span>⛔</span> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- ENCABEZADO: Título y Botón Crear --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg text-xl">👥</span>
                        <div>
                            Lista de Usuarios
                            <p class="text-xs text-gray-400 font-normal mt-0.5">Administra los accesos y roles del sistema.</p>
                        </div>
                    </h3>
                    
                    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Crear Nuevo Usuario
                    </a>
                </div>

                {{-- 🔍 BARRA DE BÚSQUEDA Y FILTROS --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        {{-- Buscador de Texto --}}
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Buscar Usuario</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" 
                                    placeholder="Nombre, correo o teléfono...">
                            </div>
                        </div>

                        {{-- Filtro de Rol --}}
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filtrar por Rol</label>
                            <select name="role" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos los Roles</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Clientes (Users)</option>
                                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Dueños (Owners)</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                            </select>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded-lg transition shadow-md dark:bg-gray-600 dark:hover:bg-gray-500">
                                Filtrar
                            </button>
                            
                            {{-- Botón Limpiar (solo si hay filtros activos) --}}
                            @if(request()->has('search') || request()->has('role'))
                                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Limpiar Filtros">
                                    ✕
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- TABLA --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Email / Celular</th>
                                <th class="px-6 py-3 text-center">Rol Actual</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ $user->id }}</td>
                                    
                                    {{-- Nombre y Avatar --}}
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            @if($user->id === 1)
                                                <span class="text-[10px] bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded border border-yellow-200 font-bold">👑 SUPER ADMIN</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Contacto --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                                        <div class="text-xs text-indigo-500 font-bold flex items-center gap-1">
                                            <span>📱</span> {{ $user->phone ?? 'Sin celular' }}
                                        </div>
                                    </td>

                                    {{-- Rol --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full border border-red-200 shadow-sm">🛡️ Admin</span>
                                        @elseif($user->role === 'owner')
                                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-1 rounded-full border border-purple-200 shadow-sm">⚽ Dueño</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-200 shadow-sm">👤 Cliente</span>
                                        @endif
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_blocked)
                                            <span class="bg-black text-white text-xs font-black px-2 py-1 rounded uppercase tracking-wider shadow-sm">🚫 BLOQUEADO</span>
                                        @else
                                            @if($user->consecutive_cancellations > 0)
                                                <span class="text-orange-600 font-bold text-xs bg-orange-50 px-2 py-1 rounded border border-orange-100">⚠️ {{ $user->consecutive_cancellations }} Strikes</span>
                                            @else
                                                <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-100">✅ Limpio</span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            
                                            {{-- CASO 1: SUPER ADMIN (ID 1) --}}
                                            @if($user->id === 1)
                                                <span class="text-xs text-gray-400 italic">🔒 Intocable</span>
                                            
                                            {{-- CASO 2: TU PROPIO USUARIO --}}
                                            @elseif($user->id === Auth::id())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    Yo
                                                </span>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition" title="Editar mis datos">
                                                    ✏️
                                                </a>

                                            {{-- CASO 3: OTROS USUARIOS --}}
                                            @else
                                                {{-- Editar --}}
                                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition shadow-sm" title="Editar">
                                                    ✏️
                                                </a>

                                                {{-- Bloquear/Desbloquear --}}
                                                <form action="{{ route('admin.users.toggleBlock', $user) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" 
                                                        onclick="return confirm('¿Seguro que quieres {{ $user->is_blocked ? 'desbloquear' : 'bloquear' }} a este usuario?')"
                                                        class="px-2 py-1 text-xs font-bold uppercase rounded-lg transition shadow-sm border {{ $user->is_blocked ? 'bg-green-100 text-green-700 border-green-300 hover:bg-green-200' : 'bg-amber-100 text-amber-700 border-amber-300 hover:bg-amber-200' }}">
                                                        {{ $user->is_blocked ? '🔓' : '🔒' }}
                                                    </button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                        onclick="return confirm('⛔ ¿ESTÁS SEGURO?\n\nEsta acción eliminará al usuario y todas sus relaciones permanentemente.')"
                                                        class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition shadow-sm ml-1" title="Eliminar definitivamente">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="text-4xl mb-2">🔍</span>
                                            <p class="text-gray-500 font-bold">No se encontraron usuarios.</p>
                                            <p class="text-sm text-gray-400">Intenta con otro término de búsqueda o filtro.</p>
                                            <a href="{{ route('admin.users.index') }}" class="mt-4 text-indigo-600 hover:underline text-sm font-bold">Limpiar filtros</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>