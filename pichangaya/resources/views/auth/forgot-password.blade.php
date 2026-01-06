<x-guest-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\auth\forgot-password.blade.php --}}
    <x-authentication-card>
        <x-slot name="logo">
            {{-- 
                 ✨ EFECTO DE BRILLO BLANCO PURO ✨
                 bg-white: Fondo blanco sólido
                 rounded-full: Forma circular
                 shadow-[...]: Sombra personalizada blanca muy intensa y difusa
            --}}
            <div class="p-4 bg-white rounded-full shadow-[0_0_60px_20px_rgba(255,255,255,1)] flex items-center justify-center">
                <x-authentication-card-logo />
            </div>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecerla que te permitirá elegir una nueva.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Enviar enlace de recuperación') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>