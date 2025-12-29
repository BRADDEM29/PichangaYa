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
            
            // Lógica de validación visual de contraseña
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
            },

            validateForm(e) {
                this.errors = {};
                let isValid = true;

                if (!this.name) { this.errors.name = 'Nombre requerido'; isValid = false; }
                if (!this.email) { this.errors.email = 'Falta completar el email'; isValid = false; }
                if (!this.phone) { this.errors.phone = 'Número de celular requerido'; isValid = false; }
                if (!this.password) { this.errors.password = 'Contraseña requerida'; isValid = false; }
                if (!this.password_confirmation) { this.errors.password_confirmation = 'Confirmación requerida'; isValid = false; }
                if (this.password !== this.password_confirmation) { this.errors.password_confirmation = 'Las contraseñas no coinciden'; isValid = false; }
                
                // Nuevos Checkboxes
                if (!document.getElementById('marketing_consent').checked) {
                    this.errors.marketing_consent = 'Debes aceptar el uso de tu correo.';
                    isValid = false;
                }
                if (!document.getElementById('age_verification').checked) {
                    this.errors.age_verification = 'Debes confirmar tu edad.';
                    isValid = false;
                }

                if (!isValid) e.preventDefault();
            }
        }" @submit="validateForm">
            @csrf

            <div>
                <x-label for="name" value="Nombre Completo" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" x-model="name" required autofocus autocomplete="name" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.name" x-text="errors.name"></span>
            </div>

            <div class="mt-4">
                <x-label for="email" value="Correo Electrónico" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" x-model="email" required autocomplete="username" />
                <span class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></span>
            </div>

            <div class="mt-4">
                <x-label for="phone" value="Celular (Yape/Plin)" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm">📞</span>
                    <x-input id="phone" class="block mt-1 w-full pl-10" type="tel" name="phone" x-model="phone" required autocomplete="tel" placeholder="999 999 999" />
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></span>
            </div>

            {{-- INPUT DE CONTRASEÑA CON MEDIDOR --}}
            <div class="mt-4">
                <x-label for="password" value="Contraseña" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full" ::type="showPassword ? 'text' : 'password'" name="password" x-model="password" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" @click="showPassword = !showPassword">
                        <template x-if="!showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                    </button>
                </div>

                {{-- RESTAURADO: MEDIDOR DE FUERZA Y REQUISITOS --}}
                <div class="mt-2" x-show="password.length > 0">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-semibold text-gray-600" x-text="strengthLabel"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
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
                <span class="text-red-500 text-xs mt-1" x-show="errors.password" x-text="errors.password"></span>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmar Contraseña" />
                <div class="relative">
                    <x-input id="password_confirmation" class="block mt-1 w-full" ::type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" @click="showConfirmPassword = !showConfirmPassword">
                        <template x-if="!showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                        <template x-if="showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
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

            {{-- 🟢 NUEVO: CHECKBOX DE MARKETING Y PRIVACIDAD --}}
            <div class="mt-4">
                <x-label for="marketing_consent">
                    <div class="flex items-start">
                        <x-checkbox name="marketing_consent" id="marketing_consent" required />
                        <div class="ml-2 text-sm text-gray-600 leading-tight text-justify">
                            Haz clic aquí si estás de acuerdo con compartir tu correo electrónico con <strong>PichangaYa</strong>. 
                            Usará tu dirección de correo electrónico con fines de marketing y para otros propósitos de conformidad con su 
                            <a target="_blank" href="{{ route('policy.show') }}" class="text-blue-600 underline hover:text-blue-800 font-bold">
                                política de privacidad
                            </a>, así que te animamos a leerla.
                        </div>
                    </div>
                </x-label>
                <span class="text-red-500 text-xs mt-1" x-show="errors.marketing_consent" x-text="errors.marketing_consent"></span>
            </div>

            {{-- 🟢 NUEVO: CHECKBOX DE EDAD / ESTUDIANTE --}}
            <div class="mt-4">
                <x-label for="age_verification">
                    <div class="flex items-center">
                        <x-checkbox name="age_verification" id="age_verification" required />
                        <div class="ml-2 text-sm text-gray-600">
                            Confirmo que soy <strong>mayor de 13 años</strong>.
                        </div>
                    </div>
                </x-label>
                <span class="text-red-500 text-xs mt-1" x-show="errors.age_verification" x-text="errors.age_verification"></span>
            </div>

            {{-- 🟢 NUEVO: ENLACE DE AYUDA --}}
            <div class="mt-4 text-center text-sm text-gray-500">
                ¿Necesitas ayuda? 
                <a href="{{ route('contact.index') }}" class="text-blue-600 hover:underline font-bold">Contacta con nosotros</a>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    ¿Ya estás registrado?
                </a>

                <x-button class="ms-4 bg-green-600 hover:bg-green-700 active:bg-green-800">
                    Registrarse
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>