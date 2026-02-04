<div>
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
                {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
            </div>

            <div class="mt-5">
                <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                    {{ __('Eliminar Cuenta') }}
                </x-danger-button>
            </div>

            <x-dialog-modal wire:model.live="confirmingUserDeletion">
                <x-slot name="title">
                    {{ __('Eliminar Cuenta') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('¿Estás seguro de que deseas eliminar tu cuenta? Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}

                    <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-input type="password" class="mt-1 block w-3/4"
                                    autocomplete="current-password"
                                    placeholder="{{ __('Contraseña') }}"
                                    x-ref="password"
                                    wire:model="password"
                                    wire:keydown.enter="deleteUser" />

                        <x-input-error for="password" class="mt-2" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                        {{ __('Eliminar Cuenta') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </x-slot>
    </x-action-section>
</div>