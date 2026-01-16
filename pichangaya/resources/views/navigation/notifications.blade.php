{{-- resources/views/navigation/notifications.blade.php --}}

@php
    // 1. Lógica de Notificaciones (Tu código original)
    $allNotifications = auth()->user()->unreadNotifications;
    $filteredNotifications = $allNotifications->filter(function ($notification) {
        if (isset($notification->data['reserva_id'])) {
            $reserva = \App\Models\Reserva::find($notification->data['reserva_id']);
            if (!$reserva || $reserva->status !== 'pending') return false; 
        }
        return true;
    });
    $count = $filteredNotifications->count();

    // 2. 🟢 LÓGICA NUEVA: Detectar si hay partida en curso (Matchmaking)
    $activeSlot = auth()->user()->currentLobbySlot; // Usamos la relación del modelo User
    $lobby = $activeSlot ? $activeSlot->lobby : null;
    $lobbyActive = $lobby && in_array($lobby->status, ['searching', 'confirming']);
@endphp

<div class="ml-3 relative" 
     x-data="{ 
         open: false, 
         unreadCount: {{ $count }},
         
         // INICIAR EL POLLING (Auto-recarga)
         init() {
             // Revisar cada 15 segundos
             setInterval(() => {
                 this.checkNotifications();
             }, 15000);
         },

         // Función que pregunta al servidor
         checkNotifications() {
             fetch('{{ route('notifications.checkNew') }}')
                 .then(response => response.json())
                 .then(data => {
                     this.unreadCount = data.count;
                     document.getElementById('notification-list-container').innerHTML = data.html;
                 });
         },

         markAndRedirect(id, url, element) {
             this.unreadCount = Math.max(0, this.unreadCount - 1);
             element.style.opacity = '0.5';
             fetch(`/notifications/${id}/mark-read`, {
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Content-Type': 'application/json'
                 }
             }).then(() => {
                 if(url && url !== '#') window.location.href = url;
             }).catch(() => { if(url && url !== '#') window.location.href = url; });
         }
     }">

    {{-- BOTÓN DE LA CAMPANA --}}
    <button @click="open = ! open" id="tour-notificaciones" class="relative p-1 rounded-full text-gray-400 hover:text-white focus:outline-none transition-colors">
        <span class="sr-only">Notificaciones</span>
        
        {{-- Si está buscando partida, cambiamos el icono de la campana por un radar --}}
        @if($lobbyActive)
             <svg class="h-6 w-6 text-green-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        @else
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        @endif
        
        {{-- PUNTO ROJO (Notificaciones) --}}
        <span x-show="unreadCount > 0 || {{ $alertEmail ? 'true' : 'false' }} || {{ $alertPhone ? 'true' : 'false' }}" 
              x-transition.opacity
              class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-red-500 animate-pulse"></span>
        
        {{-- PUNTO VERDE (Matchmaking Activo) - Solo si no hay notificaciones rojas --}}
        @if($lobbyActive)
            <span x-show="unreadCount === 0" class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-green-500 animate-ping"></span>
        @endif
    </button>

    {{-- CONTENIDO DEL DROPDOWN --}}
    <div x-show="open" @click.away="open = false" style="display: none;"
            class="origin-top-right absolute right-0 mt-2 w-80 sm:w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        
        {{-- HEADER --}}
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-t-md">
            <div class="flex items-center gap-2">
                <span class="font-black text-gray-800 dark:text-gray-100">CENTRO DE ALERTAS</span>
                <span x-show="unreadCount > 0" 
                      class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full border border-red-200 font-bold"
                      x-text="unreadCount">
                </span>
            </div>
            
            <div x-show="unreadCount > 0">
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition underline decoration-dotted">
                        Marcar todo leído
                    </button>
                </form>
            </div>
        </div>

        {{-- 🟢 1. TARJETA DE MATCHMAKING (FIJADA AL INICIO) --}}
        @if($lobbyActive)
            <a href="{{ route('lobby.show', $lobby->id) }}" class="block border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-white dark:from-gray-800 dark:to-gray-900 hover:bg-blue-50 dark:hover:bg-gray-700 transition group">
                <div class="px-4 py-3 flex items-start gap-3">
                    {{-- ICONO DE ESTADO --}}
                    <div class="flex-shrink-0 mt-1">
                        @if($lobby->status === 'searching')
                            <div class="relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <svg class="h-6 w-6 text-green-500 relative" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        @else
                            <svg class="h-6 w-6 text-yellow-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @endif
                    </div>

                    {{-- INFORMACIÓN --}}
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-sm font-bold {{ $lobby->status === 'searching' ? 'text-green-600' : 'text-yellow-500' }}">
                                {{ $lobby->status === 'searching' ? 'BUSCANDO PARTIDA' : 'CONFIRMANDO' }}
                            </h4>
                            <span class="text-[10px] font-mono bg-gray-200 dark:bg-gray-700 px-1.5 rounded text-gray-600 dark:text-gray-300">
                                #{{ $lobby->id }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                            {{-- Jugadores --}}
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                {{ $lobby->slots->count() }}/14
                            </span>
                            {{-- Tiempo Restante (Countdown simple) --}}
                            <span class="flex items-center gap-1" title="Se cancela en...">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ \Carbon\Carbon::parse($lobby->expires_at)->diffForHumans(null, true) }}
                            </span>
                        </div>
                        <p class="text-[10px] text-blue-500 mt-1 font-bold group-hover:underline">Click para ver sala &rarr;</p>
                    </div>
                </div>
            </a>
        @endif

        {{-- 2. LISTA DE NOTIFICACIONES (SCROLLABLE) --}}
        <div class="max-h-[20rem] overflow-y-auto" id="notification-list-container">
            @include('navigation.partials.notifications-list')
        </div>

        {{-- FOOTER --}}
        <div class="block bg-gray-50 dark:bg-gray-700 text-center px-4 py-2 border-t border-gray-100 dark:border-gray-600 rounded-b-md">
            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 w-full block uppercase tracking-wide">
                Ver historial completo
            </a>
        </div>
    </div>
</div>

{{-- SCRIPT DE TEMPORIZADORES (Global) - NO TOCAR, ES IMPORTANTE PARA RESERVAS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ejecutamos updateTimers cada segundo
        setInterval(() => {
            const timerElements = document.querySelectorAll('.notif-timer');
            const now = new Date().getTime();
            timerElements.forEach(el => {
                const expiry = parseInt(el.getAttribute('data-expiry'));
                const distance = expiry - now;
                if (distance < 0) {
                    el.innerHTML = "EXPIRADO";
                    el.classList.remove('text-green-400', 'animate-pulse', 'drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]');
                    el.classList.add('text-red-600', 'drop-shadow-[0_0_8px_rgba(220,38,38,0.6)]'); 
                    return;
                }
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                el.innerHTML = `${minutes < 10 ? '0'+minutes : minutes}:${seconds < 10 ? '0'+seconds : seconds}`;
                if(minutes < 2) {
                    el.classList.remove('text-green-400', 'drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]');
                    el.classList.add('text-red-500', 'animate-pulse', 'drop-shadow-[0_0_8px_rgba(239,68,68,0.6)]');
                }
            });
        }, 1000);
    });
</script>