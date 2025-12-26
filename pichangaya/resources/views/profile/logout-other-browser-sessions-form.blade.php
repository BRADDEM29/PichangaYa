<x-action-section>
    <x-slot name="title">
        <span class="dark:text-gray-100">{{ __('Sesiones de Navegador') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="dark:text-gray-400">{{ __('Administra tus sesiones activas en otros navegadores.') }}</span>
    </x-slot>

    <x-slot name="content">
        <div class="absolute inset-0 dark:bg-gray-900 -z-10 rounded-md"></div>
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Si es necesario, puedes cerrar sesión en todas tus otras sesiones de navegador.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-5 space-y-6">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center">
                        <div>
                            <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ms-3">
                            <div class="text-sm text-gray-600 dark:text-gray-200">{{ $session->agent->platform() }} - {{ $session->agent->browser() }}</div>
                            <div class="text-xs text-gray-500">{{ $session->ip_address }}, @if ($session->is_current_device) <span class="text-green-500 font-semibold">{{ __('Este dispositivo') }}</span> @endif</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5">
            <x-button wire:click="confirmLogout" wire:loading.attr="disabled">{{ __('Cerrar Sesión') }}</x-button>
        </div>
    </x-slot>
</x-action-section>