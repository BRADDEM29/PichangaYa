<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Crear Nuevo Usuario') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700">
                
                {{-- Encabezado del Card --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 px-8 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Formulario de Registro</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Complete la información para dar de alta un nuevo usuario.</p>
                    </div>
                </div>

                <div class="p-8">
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
                                  if (this.password.length === 0) return 'Vacía';
                                  if (s <= 2) return 'Débil';
                                  if (s === 3) return 'Buena';
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
                                <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2 mb-4">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.5 2-2 2h4c-1.5 0-2-1.116-2-2z" /></svg>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Datos Generales</h3>
                                </div>
                                
                                {{-- Nombre --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="name" value="{{ old('name') }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" placeholder="Ej. Juan Pérez" required>
                                    </div>
                                    @error('name') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="email" name="email" value="{{ old('email') }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" placeholder="correo@ejemplo.com" required>
                                    </div>
                                    @error('email') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                                </div>

                                {{-- Celular Principal --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular Principal</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="phone" value="{{ old('phone') }}" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" placeholder="999 999 999" required>
                                    </div>
                                    @error('phone') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
                                </div>

                                {{-- ROL --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol de Usuario</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <select name="role" x-model="role" class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer shadow-sm">
                                            <option value="user">Cliente (Usuario)</option>
                                            <option value="owner">Dueño de Cancha</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- COLUMNA DERECHA: Seguridad (Contraseñas) --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2 mb-4">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Seguridad de la Cuenta</h3>
                                </div>

                                {{-- Contraseña --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </div>
                                        <input :type="showPassword ? 'text' : 'password'" 
                                               name="password" 
                                               x-model="password" 
                                               class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition pr-10 shadow-sm" 
                                               required placeholder="••••••••">
                                        
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 cursor-pointer" @click="showPassword = !showPassword">
                                            <template x-if="!showPassword">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </template>
                                            <template x-if="showPassword">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </template>
                                        </button>
                                    </div>
                                    @error('password') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror

                                    {{-- MEDIDOR DE FUERZA MEJORADO --}}
                                    <div class="mt-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-100 dark:border-gray-600" x-show="password.length > 0" x-transition>
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Seguridad</span>
                                            <span class="text-xs font-bold" x-text="strengthLabel" :class="{'text-red-500': passwordStrength <= 2, 'text-yellow-500': passwordStrength === 3, 'text-green-500': passwordStrength === 4}"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-600 mb-3 overflow-hidden">
                                            <div class="h-1.5 rounded-full transition-all duration-500 ease-out" 
                                                 :class="strengthColor" 
                                                 :style="'width: ' + strengthWidth"></div>
                                        </div>

                                        <ul class="grid grid-cols-2 gap-x-2 gap-y-1">
                                            <li class="flex items-center text-xs" :class="password.length >= 8 ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400'">
                                                <svg class="w-3 h-3 mr-1.5" :class="password.length >= 8 ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                Min. 8 caracteres
                                            </li>
                                            <li class="flex items-center text-xs" :class="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400'">
                                                <svg class="w-3 h-3 mr-1.5" :class="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                Mayús. y minús.
                                            </li>
                                            <li class="flex items-center text-xs" :class="(/\d/.test(password)) ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400'">
                                                <svg class="w-3 h-3 mr-1.5" :class="(/\d/.test(password)) ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                Un número
                                            </li>
                                            <li class="flex items-center text-xs" :class="(/[^A-Za-z0-9]/.test(password)) ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400'">
                                                <svg class="w-3 h-3 mr-1.5" :class="(/[^A-Za-z0-9]/.test(password)) ? 'text-green-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                Símbolo ($@!%*)
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Confirmar Contraseña --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Contraseña</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input :type="showConfirmPassword ? 'text' : 'password'" 
                                               name="password_confirmation" 
                                               x-model="password_confirmation"
                                               class="pl-10 w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 transition pr-10 shadow-sm" 
                                               required placeholder="Repita la contraseña">
                                        
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
                                        <div class="flex items-center text-red-500 text-xs mt-1 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Las contraseñas no coinciden.
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN EXTRA: SOLO PARA DUEÑOS --}}
                        <div x-show="role === 'owner'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                             
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        Teléfonos Adicionales
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">Números para gestión de canchas.</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700 space-y-3">
                                <template x-for="(phone, index) in phones" :key="index">
                                    <div class="flex gap-2 items-center" x-transition>
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                            </div>
                                            <input type="text" name="secondary_phones[]" x-model="phones[index]" placeholder="Número adicional..." class="pl-9 w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                                        </div>
                                        <button type="button" @click="phones.splice(index, 1)" class="p-2 text-red-400 hover:text-red-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:shadow-sm transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                
                                <button type="button" @click="phones.push('')" class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-500 dark:text-gray-400 hover:border-indigo-500 hover:text-indigo-500 dark:hover:border-indigo-400 dark:hover:text-indigo-400 transition flex items-center justify-center gap-2 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Agregar otro número
                                </button>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Cancelar
                            </a>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md hover:shadow-lg transition text-sm font-bold flex items-center gap-2 transform active:scale-95">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>