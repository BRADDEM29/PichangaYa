{{-- resources/views/navigation/partials/notifications-list.blade.php --}}

@php
    $activeSlot = auth()->user()->currentLobbySlot;
    $lobby = $activeSlot ? $activeSlot->lobby : null;
@endphp

@if($lobby && in_array($lobby->status, ['searching', 'confirming']))
    <a href="{{ route('lobby.show', $lobby->id) }}" class="block border-b border-gray-100 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition group relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $lobby->status === 'searching' ? 'bg-blue-500' : 'bg-yellow-500' }}"></div>
        
        <div class="px-4 py-3 flex items-start gap-3">
            <div class="flex-shrink-0 pt-1">
                @if($lobby->status === 'searching')
                    <div class="relative flex items-center justify-center">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <svg class="relative w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                @else
                    <svg class="w-6 h-6 text-yellow-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start">
                    <p class="text-sm font-black text-gray-900 dark:text-white leading-none">
                        {{ $lobby->status === 'searching' ? 'Buscando Partida...' : '¡Confirmar Asistencia!' }}
                    </p>
                    <span class="text-[10px] font-mono bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-1.5 rounded">
                        #{{ $lobby->id }}
                    </span>
                </div>
                
                <div class="mt-2 flex items-center gap-3">
                    <div class="flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>{{ $lobby->slots->count() }}/14</span>
                    </div>

                    <div class="flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ \Carbon\Carbon::parse($lobby->expires_at)->diffForHumans(null, true, true) }} rest.</span>
                    </div>
                </div>
                
                <p class="text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-bold group-hover:underline">
                    Click para volver al Lobby &rarr;
                </p>
            </div>
        </div>
    </a>
@endif

@if(isset($alertEmail) && $alertEmail)
    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 border-l-4 border-red-500 transition border-b border-gray-100 dark:border-gray-700">
        <div class="font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Verifica tu Correo
        </div>
        <p class="text-xs mt-1 opacity-80">Es necesario para asegurar tus reservas.</p>
    </a>
@endif

@if(isset($alertPhone) && $alertPhone)
    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-orange-900/20 border-l-4 border-orange-500 transition border-b border-gray-100 dark:border-gray-700">
        <div class="font-bold text-orange-600 dark:text-orange-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            Verifica tu Celular
        </div>
        <p class="text-xs mt-1 opacity-80">Valida tu número para confirmar partidos.</p>
    </a>
@endif

@forelse($filteredNotifications as $notification)
    <div x-data="{ visible: true }" x-show="visible" x-transition.duration.300ms>
        <a href="#" 
           @click.prevent="visible = false; markAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}', $el)"
           class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700 relative overflow-hidden group cursor-pointer">
            
            @if(isset($notification->data['expiry_ts']))
                <div class="p-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 border-l-4 border-green-500">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <div class="bg-green-100 dark:bg-green-900 p-1.5 rounded-md shadow-sm">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 dark:text-gray-100 leading-none">
                                        {{ $notification->data['titulo'] ?? 'Reserva Pendiente' }}
                                    </p>
                                    <p class="text-[10px] text-green-600 dark:text-green-400 font-bold uppercase tracking-wider mt-0.5">
                                        Requiere Atención
                                    </p>
                                </div>
                            </div>
                            <span class="flex items-center gap-1 text-[10px] bg-gray-200 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded border border-gray-300 dark:border-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Activa
                            </span>
                        </div>

                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed pl-1">
                            {{ Str::limit($notification->data['mensaje'] ?? 'Gestione esta solicitud.', 80) }}
                        </p>

                        <div class="mt-2 bg-black rounded-lg p-2.5 border border-gray-700 shadow-inner flex items-center justify-between relative overflow-hidden group-timer">
                            <div class="absolute bottom-0 left-0 h-0.5 bg-green-500 animate-[pulse_2s_infinite] w-full opacity-70"></div>
                            <div class="flex flex-col z-10 pl-1">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tiempo Restante</span>
                                <span class="text-[9px] text-gray-500">Auto-cancelación</span>
                            </div>
                            <div class="z-10 flex items-center gap-2">
                                <svg class="w-3 h-3 text-green-500 animate-pulse fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                <span class="font-digital text-xl font-bold text-green-400 notif-timer tracking-widest drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]" 
                                      data-expiry="{{ $notification->data['expiry_ts'] }}">
                                    --:--
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(($notification->data['icono'] ?? '') == 'cancel')
                <div class="p-4 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 text-red-500 pt-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="w-full">
                            <p class="text-sm font-black text-red-700 dark:text-red-400 leading-none">
                                ¡Reserva Cancelada!
                            </p>
                            <p class="text-xs text-red-600 dark:text-red-300 mt-1 font-medium leading-tight">
                                {{ $notification->data['mensaje'] ?? 'La reserva ha sido anulada.' }}
                            </p>
                            <p class="mt-2 text-[10px] text-red-400 uppercase font-bold text-right">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

            @else
                <div class="px-4 py-3 flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        @if(($notification->data['icono'] ?? '') == 'currency_exchange')
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif(($notification->data['icono'] ?? '') == 'check_circle')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif(($notification->data['icono'] ?? '') == 'mail')
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @elseif(($notification->data['icono'] ?? '') == 'lightbulb')
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        @elseif(($notification->data['icono'] ?? '') == 'warning')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @elseif(($notification->data['icono'] ?? '') == 'clock')
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $notification->data['titulo'] ?? 'Notificación' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($notification->data['mensaje'] ?? '', 60) }}</p>
                        <p class="mt-1 text-[10px] text-gray-400 text-right">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endif
        </a>
    </div>
@empty
    <div class="px-4 py-12 text-center flex flex-col items-center justify-center opacity-60">
        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full mb-3 text-gray-400 dark:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Todo está tranquilo</p>
        <p class="text-gray-400 text-xs">No hay nuevas notificaciones</p>
    </div>
@endforelse