{{-- resources/views/navigation/partials/notifications-list.blade.php --}}

@php
    $activeSlot = auth()->user()->currentLobbySlot;
    $lobby = $activeSlot ? $activeSlot->lobby : null;
@endphp

{{-- 1. TARJETA DE ESTADO DE MATCHMAKING (SÓLO SI ESTÁ EN LOBBY) --}}
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
                    <p class="text-xs font-black text-gray-900 dark:text-white leading-none uppercase tracking-wider">
                        {{ $lobby->status === 'searching' ? 'Buscando Partida' : 'Confirmar Asistencia' }}
                    </p>
                    <span class="text-[10px] font-mono bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-1.5 rounded">
                        #{{ $lobby->id }}
                    </span>
                </div>
                
                <div class="mt-2 flex items-center gap-3">
                    <div class="flex items-center gap-1 text-[11px] font-bold text-gray-600 dark:text-gray-300">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        {{-- 🟢 AQUÍ LOS NÚMEROS REALES --}}
                        <span>{{ $lobby->slots->count() }}/{{ $lobby->max_slots }}</span>
                    </div>

                    <div class="flex items-center gap-1 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ \Carbon\Carbon::parse($lobby->expires_at)->diffForHumans(null, true, true) }}</span>
                    </div>
                </div>
                
                <p class="text-[10px] text-blue-600 dark:text-blue-400 mt-1.5 font-bold uppercase tracking-tighter group-hover:underline">
                    Volver a la sala
                </p>
            </div>
        </div>
    </a>
@endif

{{-- 2. ALERTAS DE VERIFICACIÓN --}}
@if(isset($alertEmail) && $alertEmail)
    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm hover:bg-red-50 dark:hover:bg-red-900/10 border-l-4 border-red-500 transition border-b border-gray-100 dark:border-gray-700">
        <div class="font-black text-red-600 dark:text-red-400 flex items-center gap-2 uppercase text-[11px] tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            Verificar Correo
        </div>
        <p class="text-[11px] mt-0.5 text-gray-500 dark:text-gray-400">Es obligatorio para realizar reservas.</p>
    </a>
@endif

@if(isset($alertPhone) && $alertPhone)
    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm hover:bg-orange-50 dark:hover:bg-orange-900/10 border-l-4 border-orange-500 transition border-b border-gray-100 dark:border-gray-700">
        <div class="font-black text-orange-600 dark:text-orange-400 flex items-center gap-2 uppercase text-[11px] tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            Validar Celular
        </div>
        <p class="text-[11px] mt-0.5 text-gray-500 dark:text-gray-400">Necesario para confirmar tu asistencia.</p>
    </a>
@endif

{{-- 3. LISTADO DE NOTIFICACIONES GENERALES --}}
@forelse($filteredNotifications as $notification)
    <div x-data="{ visible: true }" x-show="visible" x-transition.duration.300ms>
        <a href="#" 
           @click.prevent="visible = false; markAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}', $el)"
           class="block hover:bg-gray-50 dark:hover:bg-gray-800/50 transition border-b border-gray-100 dark:border-gray-700 relative overflow-hidden group cursor-pointer">
            
            {{-- CASO A: NOTIFICACIÓN CON TIEMPO (RESERVAS) --}}
            @if(isset($notification->data['expiry_ts']))
                <div class="p-4 bg-white dark:bg-gray-900 border-l-4 border-green-500">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="bg-green-100 dark:bg-green-900/30 p-1.5 rounded shadow-sm">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-[11px] font-black text-gray-800 dark:text-gray-100 uppercase tracking-widest">
                                    {{ $notification->data['titulo'] ?? 'Reserva' }}
                                </p>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-tight">
                            {{ Str::limit($notification->data['mensaje'] ?? '', 80) }}
                        </p>

                        <div class="mt-1 bg-gray-50 dark:bg-black rounded border border-gray-200 dark:border-gray-800 p-2 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Expira en</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="font-mono text-sm font-bold text-green-600 dark:text-green-400 notif-timer" 
                                      data-expiry="{{ $notification->data['expiry_ts'] }}">
                                    00:00
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- CASO B: NOTIFICACIÓN ESTÁNDAR --}}
            @else
                <div class="px-4 py-3 flex items-start gap-3">
                    <div class="flex-shrink-0 pt-0.5">
                        @php
                            $icono = $notification->data['icono'] ?? 'default';
                            $colorClass = 'text-blue-500';
                            if($icono == 'cancel' || $icono == 'warning') $colorClass = 'text-red-500';
                            if($icono == 'check_circle') $colorClass = 'text-green-500';
                            if($icono == 'currency_exchange' || $icono == 'lightbulb') $colorClass = 'text-yellow-500';
                            if($icono == 'clock') $colorClass = 'text-orange-500';
                        @endphp
                        
                        <div class="{{ $colorClass }}">
                            @if($icono == 'currency_exchange')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($icono == 'check_circle')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($icono == 'cancel')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($icono == 'clock')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-black text-gray-900 dark:text-gray-100 uppercase tracking-tight">
                            {{ $notification->data['titulo'] ?? 'Notificación' }}
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                            {{ $notification->data['mensaje'] ?? '' }}
                        </p>
                        <p class="mt-1.5 text-[9px] text-gray-400 uppercase font-bold tracking-tighter">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endif
        </a>
    </div>
@empty
    {{-- ESTADO VACÍO --}}
    <div class="px-4 py-10 text-center flex flex-col items-center justify-center">
        <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-full mb-3 text-gray-300 dark:text-gray-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-black uppercase tracking-widest">Sin Notificaciones</p>
        <p class="text-gray-400 dark:text-gray-500 text-[10px] mt-1">Te avisaremos cuando algo ocurra.</p>
    </div>
@endforelse