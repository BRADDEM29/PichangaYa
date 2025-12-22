<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                {{-- Formulario con AlpineJS para lógica dinámica --}}
                <form method="POST" action="{{ route('admin.users.store') }}" 
                      x-data="{ 
                          role: 'user', 
                          phones: [] // Array para teléfonos extra
                      }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombre Completo</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Contraseña</label>
                            <input type="password" name="password" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                        </div>

                        {{-- ROL (Controla la vista) --}}
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
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-indigo-500" required>
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- 🟢 SECCIÓN SOLO PARA DUEÑOS: TELÉFONOS EXTRA --}}
                    <div x-show="role === 'owner'" x-transition class="mt-8 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-purple-800 dark:text-purple-300">📱 Teléfonos Adicionales (Opcional)</h3>
                            <button type="button" @click="phones.push('')" class="text-xs bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700">
                                + Agregar otro
                            </button>
                        </div>

                        <div class="space-y-3">
                            {{-- Input inicial si quieres mostrar uno vacío, o dejar que el botón agregue --}}
                            <template x-for="(phone, index) in phones" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" name="secondary_phones[]" placeholder="Otro número de celular..." class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
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
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold transition">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>