<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\profile\show.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- 🟢 SECCIÓN DE VERIFICACIÓN --}}
            <div id="verification-section" class="md:grid md:grid-cols-3 md:gap-6 mb-10">
                <x-section-title>
                    <x-slot name="title">Verificación de Contacto</x-slot>
                    <x-slot name="description">Verifica tu correo o tu celular para poder realizar reservas.</x-slot>
                </x-section-title>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-lg space-y-6">
                        
                        {{-- 1. VERIFICACIÓN DE CORREO --}}
                        <div class="pb-6 border-b border-gray-100 dark:border-gray-700"
                             x-data="{ 
                                step: 'init', 
                                code: '', 
                                loading: false, 
                                message: '', 
                                error: '',
                                async sendEmailCode() {
                                    this.loading = true; this.error = ''; this.message = '';
                                    try {
                                        let res = await axios.post('{{ route('verification.send') }}', { channel: 'email', phone: '{{ Auth::user()->phone }}' });
                                        this.step = 'code';
                                        this.message = res.data.message; 
                                    } catch (e) {
                                        console.error(e);
                                        if (e.response && e.response.data && e.response.data.message) {
                                            this.error = 'ERROR: ' + e.response.data.message; 
                                        } else {
                                            this.error = 'Error de conexión desconocido.';
                                        }
                                    }
                                    this.loading = false;
                                },
                                async verifyEmail() {
                                    this.loading = true; this.error = '';
                                    try {
                                        await axios.post('{{ route('verification.check') }}', { code: this.code, channel: 'email' });
                                        window.location.reload();
                                    } catch (e) {
                                        this.error = e.response.data.error || 'Código incorrecto';
                                    }
                                    this.loading = false;
                                }
                             }">
                            
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Correo Electrónico
                                @if(Auth::user()->hasVerifiedEmail())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">VERIFICADO</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">PENDIENTE</span>
                                @endif
                            </h3>
                            
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 ml-7">
                                {{ Auth::user()->email }}
                            </div>

                            @if(!Auth::user()->hasVerifiedEmail())
                                <div class="mt-3 ml-7">
                                    {{-- Botón Enviar --}}
                                    <div x-show="step === 'init'">
                                        <x-button type="button" @click="sendEmailCode()" ::disabled="loading" class="bg-indigo-600 hover:bg-indigo-700">
                                            <span x-show="!loading">Enviar Código al Correo</span>
                                            <span x-show="loading">Enviando...</span>
                                        </x-button>
                                        <p x-show="error" class="text-red-600 font-bold text-xs mt-2 p-2 bg-red-50 border border-red-200 rounded" x-text="error"></p>
                                    </div>

                                    {{-- Input Código (MEJORADO DISEÑO) --}}
                                    <div x-show="step === 'code'" class="mt-4 space-y-3">
                                        {{-- Mensaje de éxito --}}
                                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-md border border-blue-100 dark:border-blue-800">
                                            ✅ <span x-text="message"></span>
                                        </div>
                                        
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Introduce el código:</label>

                                        <div class="flex items-center gap-3">
                                            <x-input type="text" x-model="code" class="w-40 text-center text-lg tracking-[0.5em] font-bold" maxlength="6" placeholder="000000" />
                                            
                                            <x-button type="button" @click="verifyEmail()" ::disabled="loading" class="bg-green-600 hover:bg-green-700 h-10">
                                                Verificar
                                            </x-button>
                                        </div>
                                        
                                        <p x-show="error" class="text-red-500 font-bold text-sm mt-2" x-text="error"></p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- 2. VERIFICACIÓN DE CELULAR --}}
                        <div x-data="{ 
                                step: 'input', 
                                phone: '{{ Auth::user()->phone }}',
                                code: '',
                                loading: false,
                                message: '',
                                error: '',
                                async sendCode() {
                                    this.loading = true; this.error = ''; this.message = '';
                                    try {
                                        await axios.post('{{ route('verification.send') }}', { channel: 'sms', phone: this.phone });
                                        this.step = 'code';
                                        this.message = 'Código simulado en LOG (storage/logs/laravel.log)';
                                    } catch (e) {
                                        this.error = e.response.data.message || 'Error al enviar código';
                                    }
                                    this.loading = false;
                                },
                                async verify() {
                                    this.loading = true; this.error = '';
                                    try {
                                        await axios.post('{{ route('verification.check') }}', { code: this.code, channel: 'sms' });
                                        window.location.reload();
                                    } catch (e) {
                                        this.error = e.response.data.error || 'Código incorrecto';
                                    }
                                    this.loading = false;
                                }
                             }">
                            
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Número de Celular
                                @if(Auth::user()->phone_verified_at)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        VERIFICADO
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PENDIENTE
                                    </span>
                                @endif
                            </h3>

                            @if(Auth::user()->phone_verified_at)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 ml-7">
                                    {{ Auth::user()->phone }}
                                </div>
                            @else
                                <div class="ml-7 mt-3">
                                    <div x-show="step === 'input'">
                                        <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Ingresa tu número:</label>
                                        <div class="flex gap-2">
                                            <x-input type="text" x-model="phone" class="w-full md:w-1/2" placeholder="987654321" />
                                            <x-button @click="sendCode()" ::disabled="loading">
                                                <span x-show="!loading">Enviar SMS (Simulado)</span>
                                                <span x-show="loading">...</span>
                                            </x-button>
                                        </div>
                                        <p x-show="error" class="text-red-500 text-xs mt-2" x-text="error"></p>
                                    </div>

                                    <div x-show="step === 'code'" class="mt-4 space-y-3">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded mb-2 border border-gray-200 dark:border-gray-600">
                                            <span x-text="message"></span>
                                        </div>
                                        <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Código SMS:</label>
                                        <div class="flex items-center gap-3">
                                            <x-input type="text" x-model="code" class="w-40 text-center text-lg tracking-[0.5em] font-bold" maxlength="6" placeholder="000000" />
                                            <x-button @click="verify()" ::disabled="loading" class="bg-green-600 hover:bg-green-700 h-10">
                                                Confirmar
                                            </x-button>
                                            <button @click="step = 'input'" class="text-xs text-gray-500 underline ml-2">Cambiar núm.</button>
                                        </div>
                                        <p x-show="error" class="text-red-500 text-xs mt-2" x-text="error"></p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            
            <x-section-border class="dark:border-gray-800" />

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-section-border class="dark:border-gray-800" />
            @endif

            @if (strtolower(Auth::user()->role) === 'owner')
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.manage-user-phones')
                </div>
                <x-section-border class="dark:border-gray-800" />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
                <x-section-border class="dark:border-gray-800" />
            @endif

             <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border class="dark:border-gray-800" />
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<style>
    .dark .bg-white { background-color: #1f2937 !important; color: #f3f4f6 !important; }
    .dark .border-gray-200 { border-color: #374151 !important; }
</style>