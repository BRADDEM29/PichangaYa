<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Usuario') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                
                {{-- Encabezado del Formulario --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 px-8 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información de la Cuenta</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Actualiza los datos personales, rol y seguridad del usuario.</p>
                </div>

                <div class="p-8">
                    {{-- Inicializamos Alpine --}}
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" 
                          x-data="{ 
                              role: '{{ $user->role }}', 
                              phones: {{ json_encode($user->secondaryPhones->pluck('phone_number')) }} 
                          }">
                        @csrf
                        @method('PUT')

                        {{-- SECCIÓN 1: DATOS BÁSICOS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" required placeholder="Ej: Juan Pérez">
                                </div>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" required placeholder="correo@ejemplo.com">
                                </div>
                            </div>

                            {{-- Teléfono Principal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular Principal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" required placeholder="+51 999 999 999">
                                </div>
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol de Usuario</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <select name="role" x-model="role" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                                        <option value="user">Cliente (Usuario)</option>
                                        <option value="owner">Dueño de Cancha</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: SEGURIDAD (PASSWORD) --}}
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-6 border border-indigo-100 dark:border-indigo-800 mb-8">
                            <div class="flex items-center gap-2 mb-4 text-indigo-800 dark:text-indigo-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h4 class="font-bold text-sm uppercase tracking-wide">Cambiar Contraseña</h4>
                            </div>
                            
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-4">Deja estos campos en blanco si deseas mantener la contraseña actual.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Nueva Contraseña</label>
                                    <input type="password" name="password" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Confirmar Nueva</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 3: SOLO PARA DUEÑOS --}}
                        <div x-show="role === 'owner'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="mb-8">
                            
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Teléfonos de Contacto Adicionales
                                </h4>
                                <span class="text-xs text-gray-400">Para reservas y notificaciones</span>
                            </div>

                            <div class="space-y-3 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                <template x-for="(phone, index) in phones" :key="index">
                                    <div class="flex gap-3 items-center group">
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="secondary_phones[]" x-model="phones[index]" placeholder="Número adicional..." class="pl-9 w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        </div>
                                        <button type="button" @click="phones.splice(index, 1)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-full transition" title="Eliminar número">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="phones.push('')" class="w-full py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-500 dark:text-gray-400 hover:border-indigo-500 hover:text-indigo-500 dark:hover:border-indigo-400 dark:hover:text-indigo-400 transition flex items-center justify-center gap-2 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Agregar otro número
                                </button>
                                
                                <div x-show="phones.length === 0" class="text-center py-2">
                                    <p class="text-xs text-gray-400">No hay teléfonos adicionales configurados.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md hover:shadow-lg transition text-sm font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>