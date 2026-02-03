<x-form-section submit="updateProfileInformation">
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\profile\update-profile-information-form.blade.php --}}
    
    <x-slot name="title">
        <span class="text-gray-900 dark:text-gray-100">{{ __('Información del Perfil') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600 dark:text-gray-400">{{ __('Actualiza la información de tu cuenta, correo electrónico y número de contacto.') }}</span>
    </x-slot>

    <x-slot name="form">
        
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-6 border-b border-gray-200 dark:border-gray-700 pb-6 mb-4">
                
                {{-- Info Box Semántico --}}
                <article class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <h3 class="block font-bold text-sm text-gray-700 dark:text-gray-200">Imagen de perfil</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Si añades una foto, otras personas podrán reconocerte y sabrás si has iniciado sesión en tu cuenta.
                    </p>
                </article>

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
                    <figure>
                        <div class="mt-2" x-show="! photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover border-2 border-gray-100 dark:border-gray-700 shadow-sm">
                        </div>

                        <div class="mt-2" x-show="photoPreview" style="display: none;">
                            <span class="block rounded-full h-20 w-20 bg-cover bg-no-repeat bg-center border-2 border-gray-100 dark:border-gray-700 shadow-sm"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>
                    </figure>

                    <div class="flex flex-col gap-2">
                        <x-secondary-button class="justify-center" type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Cambiar') }}
                        </x-secondary-button>

                        {{-- 🟢 BOTÓN ELIMINAR FOTO --}}
                        @if ($this->user->profile_photo_path)
                            <x-secondary-button type="button" class="mt-2 justify-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 border-red-200 hover:bg-red-50 dark:border-red-900 dark:hover:bg-red-900/20" wire:click="deleteProfilePhoto">
                                {{ __('Eliminar') }}
                            </x-secondary-button>
                        @endif

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

        {{-- 🟢 BOTÓN OPTIMIZADO PARA UX --}}
        <x-button wire:loading.attr="disabled" wire:target="photo, updateProfileInformation" class="min-w-[120px] justify-center">
            <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Guardar') }}</span>
            
            <span wire:loading wire:target="updateProfileInformation" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Guardando...') }}
            </span>
        </x-button>
    </x-slot>
</x-form-section>