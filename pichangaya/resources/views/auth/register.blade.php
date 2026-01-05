<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        {{-- INICIO DEL FORMULARIO --}}
        <form method="POST" action="{{ route('register') }}" x-data="{
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            phone: '{{ old('phone') }}',
            password: '',
            password_confirmation: '',
            showPassword: false,
            showConfirmPassword: false,
            showPrivacyModal: false,
            marketing_consent: false,
            age_verification: false,
            errors: {},
            
            // Lógica visual de fuerza de contraseña
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

            // 1. Validar campos básicos antes de mostrar el modal
            validateBasicFields() {
                this.errors = {};
                let isValid = true;

                if (!this.name) { this.errors.name = 'Nombre requerido'; isValid = false; }
                if (!this.email) { this.errors.email = 'Falta completar el email'; isValid = false; }
                if (!this.phone) { this.errors.phone = 'Número de celular requerido'; isValid = false; }
                if (!this.password) { this.errors.password = 'Contraseña requerida'; isValid = false; }
                if (!this.password_confirmation) { this.errors.password_confirmation = 'Confirmación requerida'; isValid = false; }
                if (this.password !== this.password_confirmation) { this.errors.password_confirmation = 'Las contraseñas no coinciden'; isValid = false; }

                // Si los datos están bien, abrimos el modal
                if (isValid) {
                    this.showPrivacyModal = true;
                }
            },

            // 2. Enviar formulario final simulando clic en botón submit real
            submitForm() {
                if (!this.marketing_consent || !this.age_verification) {
                    return; 
                }
                // Hacemos click en el botón oculto para envío nativo
                this.$refs.hiddenSubmitButton.click();
            }
        }">
            @csrf

            {{-- 🛑 BOTÓN OCULTO PARA EL ENVÍO SEGURO --}}
            <input type="submit" class="hidden" x-ref="hiddenSubmitButton" />

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
                <x-label for="phone" value="Celular" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm"></span>
                    <x-input id="phone" class="block mt-1 w-full pl-10" type="tel" name="phone" x-model="phone" required autocomplete="tel" placeholder="999 999 999" />
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></span>
            </div>

            {{-- CONTRASEÑA --}}
            <div class="mt-4">
                <x-label for="password" value="Contraseña" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full pr-10" ::type="showPassword ? 'text' : 'password'" name="password" x-model="password" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showPassword = !showPassword">
                        <template x-if="!showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                    </button>
                </div>
                
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

            {{-- CONFIRMAR CONTRASEÑA --}}
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmar Contraseña" />
                <div class="relative">
                    <x-input id="password_confirmation" class="block mt-1 w-full pr-10" ::type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showConfirmPassword = !showConfirmPassword">
                        <template x-if="!showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </template>
                        <template x-if="showConfirmPassword">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>
                    </button>
                </div>
                <span class="text-red-500 text-xs mt-1" x-show="errors.password_confirmation" x-text="errors.password_confirmation"></span>
            </div>

            {{-- BOTÓN INICIAL "Continuar al Registro" --}}
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    ¿Ya estás registrado?
                </a>

                <x-button type="button" class="ms-4 bg-green-600 hover:bg-green-700" @click="validateBasicFields()">
                    Continuar al Registro
                </x-button>
            </div>

            {{-- MODAL FLOTANTE --}}
            <div x-show="showPrivacyModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4 backdrop-blur-sm"
                 style="display: none;">
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl h-[90vh] flex flex-col overflow-hidden border border-gray-200 dark:border-gray-700">
                    
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">📜 Política de Privacidad</h2>
                        <button type="button" @click="showPrivacyModal = false" class="text-gray-400 hover:text-red-500 text-2xl font-bold">
                            &times;
                        </button>
                    </div>

                    <div class="flex-1 bg-gray-100 dark:bg-gray-900 relative">
                        <iframe src="{{ route('policy.show') }}" class="w-full h-full border-none" title="Política de Privacidad"></iframe>
                    </div>

                    <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        
                        {{-- 1. CONSENTIMIENTO MARKETING --}}
                        <div class="mb-3">
                            <label for="marketing_consent_modal" class="flex items-start cursor-pointer select-none">
                                <div class="flex items-center h-5 mt-1">
                                    <input id="marketing_consent_modal" type="checkbox" name="marketing_consent" x-model="marketing_consent" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                </div>
                                <div class="ml-3 text-sm text-gray-700 dark:text-gray-300 leading-relaxed text-justify">
                                    Haz clic aquí si estás de acuerdo con compartir tu correo electrónico con <strong>PichangaYa</strong>. 
                                    Usará tu dirección de correo electrónico con fines de marketing y para otros propósitos de conformidad con su 
                                    <a target="_blank" href="{{ route('policy.show') }}" class="text-blue-600 dark:text-blue-400 underline hover:text-blue-800 font-bold">
                                        política de privacidad
                                    </a>, así que te animamos a leerla.
                                </div>
                            </label>
                        </div>

                        {{-- 2. CONFIRMACIÓN EDAD --}}
                        <div class="mb-5">
                            <label for="age_verification_modal" class="flex items-center cursor-pointer select-none">
                                <div class="flex items-center h-5">
                                    <input id="age_verification_modal" type="checkbox" name="age_verification" x-model="age_verification" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                </div>
                                <div class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-bold">
                                    Confirmo que soy mayor de 13 años.
                                </div>
                            </label>
                        </div>

                        {{-- BOTÓN FINAL QUE ACCIONA EL SUBMIT OCULTO --}}
                        <button type="button" 
                                @click="submitForm"
                                :disabled="!marketing_consent || !age_verification"
                                :class="{ 'opacity-50 cursor-not-allowed': (!marketing_consent || !age_verification), 'hover:bg-green-700 hover:shadow-lg': (marketing_consent && age_verification) }"
                                class="w-full bg-green-600 text-white font-black py-4 px-4 rounded-xl shadow transition duration-200 text-lg uppercase tracking-wide">
                            ACEPTAR Y REGISTRARME
                        </button>
                        
                        <div class="mt-3 text-center">
                            <button type="button" @click="showPrivacyModal = false" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 underline">
                                Cancelar registro
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </x-authentication-card>
</x-guest-layout>