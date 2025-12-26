<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <span class="text-gray-900 dark:text-gray-100">{{ __('Información del Perfil') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600 dark:text-gray-400">{{ __('Actualiza la información de tu cuenta, correo electrónico y número de contacto.') }}</span>
    </x-slot>

    <x-slot name="form">
        {{-- Estilo para forzar el fondo oscuro de la cartilla principal --}}
        <style>
            .dark .bg-white { background-color: #111827 !important; } {{-- gray-900 --}}
            .dark .bg-gray-50 { background-color: #1f2937 !important; } {{-- gray-800 para el área de botones --}}
        </style>

        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-6 border-b border-gray-200 dark:border-gray-700 pb-6 mb-4">
                
                {{-- Bloque de texto de Imagen de Perfil con fondo oscuro --}}
                <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <h3 class="block font-medium text-sm text-gray-700 dark:text-gray-200 font-bold">Imagen de perfil</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Si añades una foto, otras personas podrán reconocerte y sabrás si has iniciado sesión en tu cuenta.
                    </p>
                </div>

                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <div class="flex items-center gap-6">
                    <div>
                        <div class="mt-2" x-show="! photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover border-2 border-gray-100 dark:border-gray-700 shadow-sm">
                        </div>

                        <div class="mt-2" x-show="photoPreview" style="display: none;">
                            <span class="block rounded-full h-20 w-20 bg-cover bg-no-repeat bg-center border-2 border-gray-100 dark:border-gray-700 shadow-sm"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <x-secondary-button class="justify-center" type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Cambiar') }}
                        </x-secondary-button>
                        <x-input-error for="photo" class="mt-2" />
                    </div>
                </div>
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nombre') }}" class="dark:text-gray-300" />
            <x-input id="name" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700" wire:model="state.name" required />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Correo Electrónico') }}" class="dark:text-gray-300" />
            <x-input id="email" type="email" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700" wire:model="state.email" required />
            <x-input-error for="email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Número de Celular') }}" class="dark:text-gray-300" />
            <x-input id="phone" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-gray-700" wire:model="state.phone" required />
            <x-input-error for="phone" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>