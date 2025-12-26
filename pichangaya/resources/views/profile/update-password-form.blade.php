<x-form-section submit="updatePassword">
    <x-slot name="title">
        <span class="text-gray-900 dark:text-gray-100">{{ __('Actualizar Contraseña') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600 dark:text-gray-400">{{ __('Asegúrate de que tu cuenta use una contraseña larga y aleatoria.') }}</span>
    </x-slot>

    <x-slot name="form">
        {{-- Estilo para asegurar que el área de la cartilla sea oscura --}}
        <style>
            .dark .bg-white { background-color: #111827 !important; }
            .dark .bg-gray-50 { background-color: #1f2937 !important; }
        </style>

        {{-- 1. Contraseña Actual --}}
        <div class="col-span-6 sm:col-span-4" x-data="{ show: false }">
            <x-label for="current_password" value="{{ __('Contraseña Actual') }}" class="dark:text-gray-300" />
            <div class="relative mt-1">
                <x-input id="current_password" 
                         x-bind:type="show ? 'text' : 'password'" 
                         class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700 pr-10" 
                         wire:model="state.current_password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <template x-if="!show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </template>
                    <template x-if="show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </template>
                </button>
            </div>
            <x-input-error for="current_password" class="mt-2" />
        </div>

        {{-- 2. Nueva Contraseña --}}
        <div class="col-span-6 sm:col-span-4 mt-4" x-data="{ show: false }">
            <x-label for="password" value="{{ __('Nueva Contraseña') }}" class="dark:text-gray-300" />
            <div class="relative mt-1">
                <x-input id="password" 
                         x-bind:type="show ? 'text' : 'password'" 
                         class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700 pr-10" 
                         wire:model="state.password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <template x-if="!show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </template>
                    <template x-if="show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </template>
                </button>
            </div>
            <x-input-error for="password" class="mt-2" />
        </div>

        {{-- 3. Confirmar Contraseña --}}
        <div class="col-span-6 sm:col-span-4 mt-4" x-data="{ show: false }">
            <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" class="dark:text-gray-300" />
            <div class="relative mt-1">
                <x-input id="password_confirmation" 
                         x-bind:type="show ? 'text' : 'password'" 
                         class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700 pr-10" 
                         wire:model="state.password_confirmation" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <template x-if="!show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </template>
                    <template x-if="show">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </template>
                </button>
            </div>
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button>
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>