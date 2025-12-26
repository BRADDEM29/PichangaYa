<x-action-section>
    <x-slot name="title">
        <span class="dark:text-gray-100">{{ __('Autenticación de Dos Factores') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="dark:text-gray-400">{{ __('Añade seguridad adicional a tu cuenta.') }}</span>
    </x-slot>

    <x-slot name="content">
        <div class="absolute inset-0 dark:bg-gray-900 -z-10 rounded-md"></div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            @if ($this->enabled)
                {{ __('Has habilitado la autenticación de dos factores.') }}
            @else
                {{ __('No has habilitado la autenticación de dos factores.') }}
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p>{{ __('Cuando la autenticación de dos factores está habilitada, se te pedirá un token seguro durante la autenticación.') }}</p>
        </div>

        <div class="mt-5">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled">{{ __('Habilitar') }}</x-button>
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-danger-button wire:loading.attr="disabled">{{ __('Deshabilitar') }}</x-danger-button>
                </x-confirms-password>
            @endif
        </div>
    </x-slot>
</x-action-section>