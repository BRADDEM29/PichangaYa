<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\users\create.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                
                {{-- Formulario con AlpineJS integrando la lógica del medidor de contraseña --}}
                <form method="POST" action="{{ route('admin.users.store') }}" 
                      x-data="{ 
                          role: 'user', 
                          phones: [], 
                          password: '',
                          password_confirmation: '',
                          showPassword: false,
                          showConfirmPassword: false,

                          // Lógica del Medidor de Contraseña
                          get passwordStrength() {
                              let score = 0;
                              if (this.password.length >= 8) score++;
                              if (/[a-z]/.test(this.password) && /[A-Z]/.test(this.password)) score++;
                              if (/\d/.test(this.password)) score++;
                              if (/[^A-Za-z0-9]/.test(this.password)) score++;
                              return score;
                          },
                          get strengthLabel() {
                              const s = this.passwordStrength;
                              if (this.password.length === 0) return '';
                              if (s <= 2) return 'Débil';
                              if (s === 3) return 'Media';
                              return 'Fuerte';
                          },
                          get strengthColor() {
                              const s = this.passwordStrength;
                              if (s <= 2) return 'bg-red-500';
                              if (s === 3) return 'bg-yellow-500';
                              return 'bg-green-500';
                          },
                          get strengthWidth() {
                              const s = this.passwordStrength;
                              if (this.password.length === 0) return '0%';
                              return (s / 4) * 100 + '%';
                          }
                      }">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        {{-- COLUMNA IZQUIERDA: Datos Personales --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b pb-2 mb-4">Datos Personales</h3>
                            
                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">👤</span>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" class="w-full pl-10 rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Ej. Juan Pérez" required>
                                </div>
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500"></span>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-10 rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="correo@ejemplo.com" required>
                                </div>
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Celular Principal --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Celular Principal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500"></span>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full pl-10 rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="999 999 999" required>
                                </div>
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- ROL --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Rol de Usuario</label>
                                <select name="role" x-model="role" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer">
                                    <option value="user">Cliente (Usuario)</option>
                                    <option value="owner">Dueño de Cancha</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: Seguridad (Contraseñas) --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b pb-2 mb-4">Seguridad de la Cuenta</h3>

                            {{-- Contraseña --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           name="password" 
                                           x-model="password" 
                                           class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition pr-10" 
                                           required>
                                    
                                    {{-- Botón Ojo --}}
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 cursor-pointer" @click="showPassword = !showPassword">
                                        <template x-if="!showPassword">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </template>
                                        <template x-if="showPassword">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </template>
                                    </button>
                                </div>
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                                {{-- MEDIDOR DE FUERZA (Visual) --}}
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600" x-show="password.length > 0" x-transition>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nivel: <span x-text="strengthLabel" :class="{'text-red-500': passwordStrength <= 2, 'text-yellow-500': passwordStrength === 3, 'text-green-500': passwordStrength === 4}"></span></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-600 mb-2">
                                        <div class="h-1.5 rounded-full transition-all duration-500 ease-out shadow-sm" 
                                             :class="strengthColor" 
                                             :style="'width: ' + strengthWidth"></div>
                                    </div>

                                    <ul class="space-y-1">
                                        <li class="flex items-center text-xs" :class="password.length >= 8 ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400'">
                                            <span class="mr-2" x-text="password.length >= 8 ? '✓' : '○'"></span> Mínimo 8 caracteres
                                        </li>
                                        <li class="flex items-center text-xs" :class="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400'">
                                            <span class="mr-2" x-text="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? '✓' : '○'"></span> Mayúsculas y minúsculas
                                        </li>
                                        <li class="flex items-center text-xs" :class="(/\d/.test(password)) ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400'">
                                            <span class="mr-2" x-text="(/\d/.test(password)) ? '✓' : '○'"></span> Números
                                        </li>
                                        <li class="flex items-center text-xs" :class="(/[^A-Za-z0-9]/.test(password)) ? 'text-green-600 dark:text-green-400 font-bold' : 'text-gray-400'">
                                            <span class="mr-2" x-text="(/[^A-Za-z0-9]/.test(password)) ? '✓' : '○'"></span> Símbolos ($@!%*)
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Confirmar Contraseña</label>
                                <div class="relative">
                                    <input :type="showConfirmPassword ? 'text' : 'password'" 
                                           name="password_confirmation" 
                                           x-model="password_confirmation"
                                           class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition pr-10" 
                                           required>
                                    
                                    {{-- Botón Ojo Confirmar --}}
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 cursor-pointer" @click="showConfirmPassword = !showConfirmPassword">
                                        <template x-if="!showConfirmPassword">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </template>
                                        <template x-if="showConfirmPassword">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </template>
                                    </button>
                                </div>
                                <template x-if="password !== password_confirmation && password_confirmation.length > 0">
                                    <span class="text-red-500 text-xs mt-1 block">Las contraseñas no coinciden.</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN EXTRA: SOLO PARA DUEÑOS --}}
                    <div x-show="role === 'owner'" x-transition 
                         class="mt-8 p-6 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-xl">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-indigo-800 dark:text-indigo-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Teléfonos Adicionales
                            </h3>
                            <button type="button" @click="phones.push('')" class="text-xs font-bold bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700 shadow-md transition transform hover:scale-105">
                                + Agregar Número
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(phone, index) in phones" :key="index">
                                <div class="flex gap-3 items-center" x-transition>
                                    <span class="text-sm font-bold text-indigo-400" x-text="index + 1 + '.'"></span>
                                    <input type="text" name="secondary_phones[]" placeholder="Otro número de contacto..." class="flex-1 rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 border-none shadow-sm">
                                    <button type="button" @click="phones.splice(index, 1)" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="phones.length === 0" class="text-sm text-gray-500 italic bg-white dark:bg-gray-800 p-4 rounded-lg border border-dashed border-gray-300 text-center">
                                No se han agregado números adicionales.
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform hover:-translate-y-0.5">
                            Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>