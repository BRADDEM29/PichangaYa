<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" x-data="{
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            phone: '{{ old('phone') }}',
            password: '',
            password_confirmation: '',
            showPassword: false,
            showConfirmPassword: false,
            errors: {},
            validateForm(e) {
                this.errors = {};
                let isValid = true;

                if (!this.name) {
                    this.errors.name = 'Nombre requerido';
                    isValid = false;
                }
                if (!this.email) {
                    this.errors.email = 'Falta completar el email';
                    isValid = false;
                }
                // VALIDACIÓN DEL TELÉFONO
                if (!this.phone) {
                    this.errors.phone = 'Número de celular requerido';
                    isValid = false;
                }
                if (!this.password) {
                    this.errors.password = 'Contraseña requerida';
                    isValid = false;
                }
                if (!this.password_confirmation) {
                    this.errors.password_confirmation = 'Confirmación de contraseña requerida';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            },
            get strengthScore() {
                let s = 0;
                if (this.password.length >= 8) s++;
                if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) s++;
                if (/[0-9]/.test(this.password)) s++;
                if (/[^A-Za-z0-9]/.test(this.password)) s++;
                return s;
            },
            get strengthLabel() {
                if (this.password.length === 0) return '';
                if (this.strengthScore <= 2) return 'Débil';
                if (this.strengthScore === 3) return 'Medio';
                return 'Fuerte';
            },
            get strengthColor() {
                if (this.password.length === 0) return 'bg-gray-200';
                if (this.strengthScore <= 2) return 'bg-red-500';
                if (this.strengthScore === 3) return 'bg-yellow-500';
                return 'bg-green-500';
            }
        }" @submit="validateForm">
            @csrf

            <!-- Nombre -->
            <div>
                <x-label for="name" value="Nombre Completo" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" x-model="name" required autofocus autocomplete="name" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.name" x-text="errors.name"></span>
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="Correo Electrónico" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" x-model="email" required autocomplete="username" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></span>
            </div>

            <!-- ✅ NUEVO CAMPO: TELÉFONO (Estilo Jetstream) -->
            <div class="mt-4">
                <x-label for="phone" value="Número de Celular" />
                <x-input id="phone" class="block mt-1 w-full" type="tel" name="phone" x-model="phone" required placeholder="" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></span>
            </div>

            <!-- Contraseña -->
            <div class="mt-4">
                <x-label for="password" value="Contraseña" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="new-password" x-model="password" x-bind:type="showPassword ? 'text' : 'password'" />
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                        <template x-if="!showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </template>
                    </button>
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.password" x-text="errors.password"></span>

                <!-- Medidor de fuerza -->
                <div class="mt-2" x-show="password.length > 0">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-600" x-text="strengthLabel"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                        <div class="h-1.5 rounded-full transition-all duration-300" :class="strengthColor" :style="'width: ' + (strengthScore / 4 * 100) + '%'"></div>
                    </div>
                    <ul class="mt-2 text-xs space-y-1">
                        <li :class="password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                            <span x-show="password.length >= 8">✓</span><span x-show="password.length < 8">○</span> Mínimo 8 caracteres
                        </li>
                        <li :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                            <span x-show="/[A-Z]/.test(password) && /[a-z]/.test(password)">✓</span><span x-show="! (/[A-Z]/.test(password) && /[a-z]/.test(password))">○</span> Mayúsculas y minúsculas
                        </li>
                        <li :class="/[0-9]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                            <span x-show="/[0-9]/.test(password)">✓</span><span x-show="! /[0-9]/.test(password)">○</span> Números
                        </li>
                        <li :class="/[^A-Za-z0-9]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                            <span x-show="/[^A-Za-z0-9]/.test(password)">✓</span><span x-show="! /[^A-Za-z0-9]/.test(password)">○</span> Caracteres especiales
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmar Contraseña" />
                <div class="relative">
                    <x-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password" name="password_confirmation" required autocomplete="new-password" x-model="password_confirmation" x-bind:type="showConfirmPassword ? 'text' : 'password'" />
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                        <template x-if="!showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </template>
                    </button>
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.password_confirmation" x-text="errors.password_confirmation"></span>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2">
                                {!! __('Acepto los :terms_of_service y :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Términos de Servicio').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Políticas de Privacidad').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    ¿Ya estás registrado?
                </a>

                <x-button class="ms-4">
                    Registrarse
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>