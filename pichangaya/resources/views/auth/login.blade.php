<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- ✨ LOGO CON HALO DE LUZ (GLOW) BLANCO ✨ --}}
            <div class="p-4 bg-white rounded-full shadow-[0_0_60px_20px_rgba(255,255,255,1)] flex items-center justify-center">
                <x-authentication-card-logo />
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" x-data="{
            email: '{{ old('email') }}',
            password: '',
            showPassword: false,
            errors: {},
            validateForm(e) {
                this.errors = {};
                let isValid = true;

                if (!this.email) {
                    this.errors.email = 'El correo electrónico es obligatorio';
                    isValid = false;
                }
                if (!this.password) {
                    this.errors.password = 'La contraseña es obligatoria';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            }
        }" @submit="validateForm">
            @csrf

            {{-- Email --}}
            <div>
                <x-label for="email" value="Correo Electrónico" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" x-model="email" required autofocus autocomplete="username" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></span>
            </div>

            {{-- Contraseña --}}
            <div class="mt-4">
                <x-label for="password" value="Contraseña" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full pr-10" ::type="showPassword ? 'text' : 'password'" name="password" x-model="password" required autocomplete="current-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showPassword = !showPassword">
                        <template x-if="!showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                    </button>
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.password" x-text="errors.password"></span>
            </div>

            <div class="block mt-4 flex justify-between items-center">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Recordarme</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-center mt-6">
                <x-button class="w-full justify-center bg-green-600 hover:bg-green-700 py-3 text-lg font-bold shadow-lg">
                    Iniciar Sesión
                </x-button>
            </div>
        </form>

        {{-- SECCIÓN DE REGISTRO AÑADIDA --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">O únete a nosotros</span>
            </div>
        </div>

        <div class="mt-2 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">¿Nuevo en la cancha?</p>
            <a href="{{ route('register') }}" class="block w-full py-2.5 px-4 border-2 border-green-600 text-green-600 dark:text-green-400 font-bold rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors uppercase tracking-wide text-sm">
                ¡Crea tu cuenta Gratis!
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>