<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                {{-- Inicializamos Alpine con los datos existentes --}}
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" 
                      x-data="{ 
                          role: '{{ $user->role }}', 
                          // Convertimos la colección de PHP a un array JS simple de números
                          phones: {{ json_encode($user->secondaryPhones->pluck('phone_number')) }} 
                      }">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombre Completo</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                        </div>

                        {{-- Contraseña (Opcional) --}}
                        <div class="col-span-1 md:col-span-2 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Cambiar Contraseña (Dejar en blanco para mantener la actual)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Nueva Contraseña</label>
                                    <input type="password" name="password" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Confirmar Nueva</label>
                                    <input type="password" name="password_confirmation" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- ROL --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Rol de Usuario</label>
                            <select name="role" x-model="role" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500">
                                <option value="user">Cliente (Usuario)</option>
                                <option value="owner">Dueño de Cancha</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        {{-- Teléfono Principal --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Celular Principal</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    {{-- 🟢 SECCIÓN SOLO PARA DUEÑOS --}}
                    <div x-show="role === 'owner'" x-transition class="mt-8 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-purple-800 dark:text-purple-300">📱 Teléfonos Adicionales</h3>
                            <button type="button" @click="phones.push('')" class="text-xs bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700">
                                + Agregar otro
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(phone, index) in phones" :key="index">
                                <div class="flex gap-2">
                                    {{-- Usamos x-model para vincular el input al array de JS --}}
                                    <input type="text" name="secondary_phones[]" x-model="phones[index]" placeholder="Otro número de celular..." class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <button type="button" @click="phones.splice(index, 1)" class="text-red-500 hover:text-red-700 px-2 font-bold">X</button>
                                </div>
                            </template>
                            
                            <div x-show="phones.length === 0" class="text-xs text-gray-500 italic">
                                No hay teléfonos adicionales agregados.
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold transition">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>