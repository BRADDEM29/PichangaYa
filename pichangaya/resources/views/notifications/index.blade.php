<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Notificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Contenedor Principal con inversión de colores --}}
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6 border dark:border-gray-800">
                
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($notifications as $notification)
                            {{-- Fondo: Si no está leída, azul muy claro en light / azul oscuro traslúcido en dark --}}
                            <div class="py-4 flex items-start {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/20 -mx-6 px-6' }}">
                                
                                {{-- Icono Corregido (SVG en lugar de texto) --}}
                                <div class="flex-shrink-0 mr-4 mt-1">
                                    @php
                                        $iconName = $notification->data['icono'] ?? 'info';
                                        $colorClass = $notification->data['color'] ?? 'text-gray-500';
                                    @endphp
                                    
                                    <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        @if($iconName == 'check_circle')
                                            {{-- Icono de Check (✓) --}}
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @elseif($iconName == 'currency_exchange')
                                            {{-- Icono de Moneda/Intercambio --}}
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        @else
                                            {{-- Icono de Información (Default) --}}
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- Textos con inversión de colores --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $notification->data['titulo'] ?? 'Notificación' }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $notification->data['mensaje'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                {{-- Botón de acción --}}
                                <div class="ml-4 flex-shrink-0">
                                    <a href="{{ route('notifications.read', $notification->id) }}" 
                                       class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 text-sm">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.59 1.405L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="mt-4 text-lg font-medium dark:text-gray-300">{{ __('No tienes notificaciones') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>