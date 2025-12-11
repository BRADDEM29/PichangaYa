<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Información del Perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Actualiza la información de tu cuenta, correo electrónico y número de contacto.') }}
    </x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-6 border-b border-gray-200 pb-6 mb-4">
                
                {{-- Título y Descripción solicitados --}}
                <div class="mb-4">
                    <h3 class="block font-medium text-sm text-gray-700 font-bold">Imagen de perfil</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Si añades una foto, otras personas podrán reconocerte y sabrás si has iniciado sesión en tu cuenta.
                    </p>
                </div>

                {{-- Input Oculto (Maneja la subida del archivo) --}}
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
                    {{-- 1. CÍRCULO DE FOTO --}}
                    <div>
                        <div class="mt-2" x-show="! photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" 
                                 alt="{{ $this->user->name }}" 
                                 class="rounded-full h-20 w-20 object-cover border-2 border-gray-100 shadow-sm">
                        </div>

                        <div class="mt-2" x-show="photoPreview" style="display: none;">
                            <span class="block rounded-full h-20 w-20 bg-cover bg-no-repeat bg-center border-2 border-gray-100 shadow-sm"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>
                    </div>

                    {{-- 2. BOTONES DE ACCIÓN --}}
                    <div class="flex flex-col gap-2">
                        {{-- Botón Cambiar --}}
                        <x-secondary-button class="justify-center" type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Cambiar') }}
                        </x-secondary-button>

                        {{-- Botón Retirar (Solo aparece si el usuario subió una foto personalizada) --}}
                        @if ($this->user->profile_photo_path)
                            <x-secondary-button type="button" class="justify-center text-red-600 hover:text-red-800 hover:bg-red-50 border-red-200" wire:click="deleteProfilePhoto">
                                {{ __('Retirar') }}
                            </x-secondary-button>
                        @endif

                        <x-input-error for="photo" class="mt-2" />
                    </div>
                </div>
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nombre') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Correo Electrónico') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 text-gray-600">
                    {{ __('Tu dirección de correo no está verificada.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                    </p>
                @endif
            @endif
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Número de Celular') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="state.phone" required placeholder="" />
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