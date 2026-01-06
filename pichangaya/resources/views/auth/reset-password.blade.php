<x-guest-layout>
    {{-- resources/views/auth/reset-password.blade.php --}}
    <x-authentication-card>
        <x-slot name="logo">
            {{-- ✨ LOGO CON HALO DE LUZ (GLOW) ✨ --}}
            <div class="p-4 bg-white rounded-full shadow-[0_0_60px_20px_rgba(255,255,255,1)] flex items-center justify-center">
                <x-authentication-card-logo />
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}" x-data="{
            password: '',
            password_confirmation: '',
            showPassword: false,
            showConfirmPassword: false,

            // Lógica de fuerza de contraseña
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

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- CORREO ELECTRÓNICO --}}
            <div class="block">
                <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            {{-- CONTRASEÑA NUEVA (CON MEDIDOR DE FUERZA) --}}
            <div class="mt-4">
                <x-label for="password" value="{{ __('Nueva Contraseña') }}" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full pr-10" 
                             ::type="showPassword ? 'text' : 'password'" 
                             name="password" 
                             x-model="password" 
                             required autocomplete="new-password" />
                             
                    {{-- Botón Ojito --}}
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showPassword = !showPassword">
                        <template x-if="!showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                    </button>
                </div>

                {{-- Barra de Fuerza y Requisitos --}}
                <div class="mt-2" x-show="password.length > 0">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-semibold text-gray-600" x-text="strengthLabel"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all duration-300" 
                             :class="strengthColor" 
                             :style="'width: ' + strengthWidth"></div>
                    </div>
                    <ul class="mt-3 space-y-1 text-xs text-gray-500">
                        <li class="flex items-center" :class="password.length >= 8 ? 'text-green-600 font-bold' : ''">
                            <span class="mr-2" x-text="password.length >= 8 ? '✓' : '○'"></span> Mínimo 8 caracteres
                        </li>
                        <li class="flex items-center" :class="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? 'text-green-600 font-bold' : ''">
                            <span class="mr-2" x-text="(/[a-z]/.test(password) && /[A-Z]/.test(password)) ? '✓' : '○'"></span> Mayúsculas y minúsculas
                        </li>
                        <li class="flex items-center" :class="(/\d/.test(password)) ? 'text-green-600 font-bold' : ''">
                            <span class="mr-2" x-text="(/\d/.test(password)) ? '✓' : '○'"></span> Números
                        </li>
                        <li class="flex items-center" :class="(/[^A-Za-z0-9]/.test(password)) ? 'text-green-600 font-bold' : ''">
                            <span class="mr-2" x-text="(/[^A-Za-z0-9]/.test(password)) ? '✓' : '○'"></span> Caracteres especiales
                        </li>
                    </ul>
                </div>
            </div>

            {{-- CONFIRMAR CONTRASEÑA --}}
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <div class="relative">
                    <x-input id="password_confirmation" class="block mt-1 w-full pr-10" 
                             ::type="showConfirmPassword ? 'text' : 'password'" 
                             name="password_confirmation" 
                             x-model="password_confirmation" 
                             required autocomplete="new-password" />
                             
                    {{-- Botón Ojito --}}
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showConfirmPassword = !showConfirmPassword">
                        <template x-if="!showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                        <template x-if="showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Restablecer Contraseña') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>