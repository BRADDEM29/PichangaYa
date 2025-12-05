<x-action-section>
    <x-slot name="title">
        {{ __('Teléfonos Adicionales') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Agrega otros números de contacto para tus diferentes sedes (Ej: Sede Norte, Administrador).') }}
    </x-slot>

    <x-slot name="content">
        
        {{-- LISTA DE NÚMEROS GUARDADOS --}}
        <div class="space-y-3">
            @foreach ($phones as $phone)
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 text-indigo-600 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-700">{{ $phone->phone_number }}</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $phone->label ?? 'Secundario' }}</span>
                        </div>
                    </div>
                    
                    <button wire:click="deletePhone({{ $phone->id }})" class="text-red-400 hover:text-red-600 transition" title="Eliminar número">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>

        {{-- LÍNEA DIVISORIA --}}
        <div class="border-t border-gray-100 my-4"></div>

        {{-- FORMULARIO CON BOTÓN VERDE "+" --}}
        <form wire:submit.prevent="addPhone" class="flex items-start gap-2">
            
            {{-- Input Número --}}
            <div class="w-1/2">
                <x-label for="phone_number" value="{{ __('Nuevo Celular') }}" class="mb-1" />
                <x-input id="phone_number" type="text" class="block w-full" wire:model="phone_number" placeholder="987..." />
                <x-input-error for="phone_number" class="mt-1" />
            </div>

            {{-- Input Etiqueta --}}
            <div class="w-1/3">
                <x-label for="label" value="{{ __('Etiqueta') }}" class="mb-1" />
                <x-input id="label" type="text" class="block w-full" wire:model="label" placeholder="Ej: Sede Sur" />
                <x-input-error for="label" class="mt-1" />
            </div>

            {{-- BOTÓN VERDE "+" --}}
            <div class="pt-6"> {{-- Padding top para alinear con los inputs --}}
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md shadow-md transition transform hover:scale-105 flex items-center justify-center h-10 w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

        </form>
        
        {{-- Mensaje de Éxito --}}
        <x-action-message class="mt-2" on="saved">
            {{ __('Número agregado correctamente.') }}
        </x-action-message>

    </x-slot>
</x-action-section>