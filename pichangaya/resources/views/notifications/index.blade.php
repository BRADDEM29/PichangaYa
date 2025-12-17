<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Notificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($notifications as $notification)
                            <div class="py-4 flex items-start {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 -mx-6 px-6' }}">
                                
                                {{-- Icono --}}
                                <div class="flex-shrink-0 mr-4 mt-1">
                                    @php
                                        $icon = $notification->data['icono'] ?? 'info';
                                        $color = $notification->data['color'] ?? 'text-gray-500';
                                    @endphp
                                    
                                    {{-- Renderizado simple de iconos Material/Heroicons --}}
                                    @if($icon == 'currency_exchange' || $icon == 'check_circle')
                                         <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-xl {{ $color }}">
                                                {{ $icon == 'currency_exchange' ? '$' : '✓' }}
                                            </span>
                                         </div>
                                    @elseif($icon == 'cancel')
                                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <span class="text-xl text-red-600">✕</span>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-xl text-blue-600">ℹ</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Contenido --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->data['titulo'] ?? 'Notificación' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $notification->data['mensaje'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->format('d/m/Y h:i A') }} 
                                        ({{ $notification->created_at->diffForHumans() }})
                                    </p>
                                </div>

                                {{-- Botón de acción --}}
                                <div class="ml-4 flex-shrink-0">
                                    {{-- Al hacer click, va a la ruta 'markAsRead' que marca leída y luego redirige a la reserva --}}
                                    <a href="{{ route('notifications.read', $notification->id) }}" 
                                       class="font-medium text-indigo-600 hover:text-indigo-500 text-sm">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-10 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="mt-2">No tienes notificaciones registradas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>