<x-app-layout>
    <x-slot name="header">
        {{-- Forzamos texto blanco en el header si es oscuro --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight transition-colors duration-300">
            {{ __('Modificar Reserva') }}
        </h2>
    </x-slot>

    {{-- Contenedor principal con fondo adaptable y altura mínima --}}
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- La tarjeta ahora cambia de color y añade un borde sutil en modo oscuro --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-transparent dark:border-gray-700 transition-colors duration-300">
                
                {{-- Contenedor para que el texto del formulario de Livewire herede el color adecuado --}}
                <div class="text-gray-900 dark:text-white">
                    @livewire('edit-reserva-form', ['reserva' => $reserva])
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>