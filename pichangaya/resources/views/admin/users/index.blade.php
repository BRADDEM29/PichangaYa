<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\users\index.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800 dark:text-gray-200">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes Flash Mejorados --}}
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl ring-1 ring-gray-900/5 p-6">
                
                {{-- ENCABEZADO: Título y Botón Crear --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                Lista de Usuarios
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Administra los accesos, roles y sanciones del sistema.</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.users.create') }}" class="group inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:scale-110 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Crear Nuevo Usuario
                    </a>
                </div>

                {{-- 🔍 BARRA DE BÚSQUEDA Y FILTROS --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-8 bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        
                        {{-- Buscador de Texto --}}
                        <div class="md:col-span-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Buscar Usuario</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm" 
                                    placeholder="Nombre, correo o teléfono...">
                            </div>
                        </div>

                        {{-- Filtro de Rol --}}
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Filtrar por Rol</label>
                            <div class="relative">
                                <select name="role" class="appearance-none w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm pl-3 pr-10">
                                    <option value="">Todos los Roles</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Clientes (Users)</option>
                                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Dueños (Owners)</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="flex-1 bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded-lg transition shadow-md dark:bg-gray-700 dark:hover:bg-gray-600 flex items-center justify-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                                Filtrar
                            </button>
                            
                            @if(request()->has('search') || request()->has('role'))
                                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center px-4 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition shadow-sm" title="Limpiar Filtros">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- TABLA --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 font-bold">ID</th>
                                <th class="px-6 py-4 font-bold">Nombre</th>
                                <th class="px-6 py-4 font-bold">Contacto</th>
                                <th class="px-6 py-4 font-bold text-center">Rol</th>
                                <th class="px-6 py-4 font-bold text-center">Estado</th>
                                <th class="px-6 py-4 font-bold text-center">Strikes</th>
                                <th class="px-6 py-4 font-bold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ $user->id }}</td>
                                    
                                    {{-- Nombre y Avatar --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                            @endif
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1">
                                                    {{ $user->name }}
                                                </div>
                                                @if($user->id === 1)
                                                    <span class="inline-flex items-center gap-1 text-[10px] bg-yellow-50 text-yellow-700 px-1.5 py-0.5 rounded border border-yellow-200 font-bold mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                                        </svg>
                                                        SUPER ADMIN
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contacto --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-400">
                                                    <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                                    <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                                </svg>
                                                <span class="text-xs">{{ $user->email }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-indigo-400">
                                                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0117.5 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $user->phone ?? 'Sin celular' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Rol --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role === 'admin')
                                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full border border-red-100 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                </svg>
                                                Admin
                                            </span>
                                        @elseif($user->role === 'owner')
                                            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full border border-purple-100 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path fill-rule="evenodd" d="M6 3.75A.75.75 0 016.75 3h10.5a.75.75 0 01.75.75v4.312A9.195 9.195 0 0118 12.061l-2.5.462c.628.326.212 1.382-.446 1.481a6.038 6.038 0 01-1.745.053.75.75 0 01-.58-.871l.568-3.12a.75.75 0 10-1.474-.268l-.568 3.12a.75.75 0 01-.634.611 6.029 6.029 0 01-1.78-.053c-.663-.1-1.08-1.164-.446-1.482l.833-.153a.75.75 0 10-.274-1.474l-2.53.465a9.18 9.18 0 01-.618-1.579L6 3.75z" clip-rule="evenodd" />
                                                </svg>
                                                Dueño
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-100 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                                                </svg>
                                                Cliente
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_blocked)
                                            <span class="inline-flex items-center gap-1 bg-gray-900 text-white text-[10px] font-bold px-2 py-1 rounded border border-gray-700 uppercase tracking-wide shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                                                </svg>
                                                Bloqueado
                                            </span>
                                        @else
                                            @if($user->consecutive_cancellations > 0)
                                                <span class="inline-flex items-center gap-1 text-orange-700 font-bold text-xs bg-orange-50 px-2 py-1 rounded border border-orange-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $user->consecutive_cancellations }} Strikes
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-green-700 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                    </svg>
                                                    Limpio
                                                </span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- 🟢 GESTIÓN DE STRIKES --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$user->is_blocked && $user->role === 'user')
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                                                    {{ $user->consecutive_cancellations }}
                                                </span>
                                                
                                                <form action="{{ route('admin.users.update_strikes', $user) }}" method="POST" class="flex items-center gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <button type="submit" name="strikes" value="{{ max(0, $user->consecutive_cancellations - 1) }}" 
                                                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-700 rounded border border-gray-200 transition"
                                                            title="Quitar un Strike">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    
                                                    <button type="submit" name="strikes" value="{{ $user->consecutive_cancellations + 1 }}" 
                                                            class="w-6 h-6 flex items-center justify-center bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-700 rounded border border-gray-200 transition"
                                                            title="Agregar un Strike">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <span class="text-gray-300 text-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            
                                            {{-- CASO 1: SUPER ADMIN (ID 1) --}}
                                            @if($user->id === 1)
                                                <span class="flex items-center gap-1 text-xs text-gray-400 italic bg-gray-50 px-2 py-1 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                    </svg>
                                                    Protegido
                                                </span>
                                            
                                            {{-- CASO 2: TU PROPIO USUARIO --}}
                                            @elseif($user->id === Auth::id())
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A9.948 9.948 0 0010 18c1.694 0 3.298-.446 4.707-1.221A5.99 5.99 0 0010 12z" clip-rule="evenodd" />
                                                    </svg>
                                                    Tú
                                                </span>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 rounded-lg transition" title="Editar mis datos">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>

                                            {{-- CASO 3: OTROS USUARIOS --}}
                                            @else
                                                {{-- Editar --}}
                                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition shadow-sm" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>

                                                {{-- Bloquear/Desbloquear --}}
                                                <form action="{{ route('admin.users.toggleBlock', $user) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" 
                                                        onclick="return confirm('¿Seguro que quieres {{ $user->is_blocked ? 'desbloquear' : 'bloquear' }} a este usuario?')"
                                                        class="p-2 rounded-lg transition shadow-sm {{ $user->is_blocked ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-amber-50 text-amber-600 hover:bg-amber-100' }}"
                                                        title="{{ $user->is_blocked ? 'Desbloquear' : 'Bloquear' }}">
                                                        
                                                        @if($user->is_blocked)
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                        onclick="return confirm('⛔ ¿ESTÁS SEGURO?\n\nEsta acción eliminará al usuario y todas sus relaciones permanentemente.')"
                                                        class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition shadow-sm ml-1" title="Eliminar definitivamente">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-900 font-bold mb-1">No se encontraron usuarios.</p>
                                            <p class="text-sm text-gray-500 mb-4">Intenta con otro término de búsqueda o cambia los filtros.</p>
                                            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm font-semibold flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Limpiar filtros
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                @if($users->hasPages())
                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>