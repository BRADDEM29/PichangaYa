<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
            {{-- 1. INFORMACIÓN PRINCIPAL --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-section-border />
            @endif

            {{-- ✅ 2. GESTIÓN DE TELÉFONOS (MOVIDO AQUÍ ARRIBA) --}}
            {{-- Usamos strtolower para asegurar que 'Owner' u 'owner' funcionen igual --}}
            @if (strtolower(Auth::user()->role) === 'owner')
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.manage-user-phones')
                </div>
                <x-section-border />
            @endif

            {{-- 3. CONTRASEÑA --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
                <x-section-border />
            @endif

            {{-- 4. DOBLE FACTOR --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
                <x-section-border />
            @endif

            {{-- 5. SESIONES --}}
            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- 6. BORRAR CUENTA --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>