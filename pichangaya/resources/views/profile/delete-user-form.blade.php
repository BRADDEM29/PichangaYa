<x-action-section>
    <x-slot name="title">
        <span class="dark:text-gray-100">{{ __('Eliminar Cuenta') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="dark:text-gray-400">{{ __('Eliminar permanentemente tu cuenta.') }}</span>
    </x-slot>

    <x-slot name="content">
        <div class="absolute inset-0 dark:bg-gray-900 -z-10 rounded-md"></div>
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">{{ __('Eliminar Cuenta') }}</x-danger-button>
        </div>
    </x-slot>
</x-action-section>