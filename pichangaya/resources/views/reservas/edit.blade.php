{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\reservas\edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 leading-tight">
                {{ __('Modificar Reserva') }}
            </h2>
            
            {{-- Botón VOLVER --}}
            <a href="{{ route('reservas.user.index') }}" class="group flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-[#0f172a] min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- CONTENEDOR PRINCIPAL --}}
            <div class="bg-white/80 dark:bg-gray-800/90 backdrop-blur-xl shadow-2xl sm:rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                {{-- CABECERA DECORATIVA: Resumen de lo que se edita --}}
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-900 dark:to-purple-900 p-6 sm:p-8 text-white relative overflow-hidden">
                    {{-- Decoración de fondo --}}
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-black/10 rounded-full blur-xl"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-2 opacity-80 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-wider">Editando Reserva #{{ $reserva->id }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white">{{ $reserva->cancha->name }}</h3>
                            <p class="text-indigo-100 text-sm flex items-center gap-2 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                Fecha actual: {{ $reserva->start_time->format('d M, Y') }}
                            </p>
                        </div>
                        
                        <div class="bg-white/20 backdrop-blur-md rounded-lg p-3 text-center border border-white/30">
                            <span class="block text-xs text-indigo-50 uppercase font-bold">Total Actual</span>
                            <span class="block text-xl font-extrabold text-white">S/ {{ number_format($reserva->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- CUERPO DEL FORMULARIO --}}
                <div class="p-6 sm:p-8">
                    {{-- Aviso informativo --}}
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Cambiar la fecha o duración puede afectar el precio final de la reserva.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- LIVEWIRE COMPONENT --}}
                    <div class="text-gray-900 dark:text-gray-100">
                        @livewire('edit-reserva-form', ['reserva' => $reserva])
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    {{-- Footer opcional --}}
    <div class="relative z-10">
        <x-footer />
    </div>

</x-app-layout>