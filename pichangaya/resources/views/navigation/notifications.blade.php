{{-- resources/views/navigation/notifications.blade.php --}}

@php
    // 1. Lógica de Notificaciones
    $allNotifications = auth()->user()->unreadNotifications;
    $filteredNotifications = $allNotifications->filter(function ($notification) {
        if (isset($notification->data['reserva_id'])) {
            $reserva = \App\Models\Reserva::find($notification->data['reserva_id']);
            if (!$reserva || $reserva->status !== 'pending') return false; 
        }
        return true;
    });
    $count = $filteredNotifications->count();

    // 2. Lógica de Matchmaking (Lobby)
    $activeSlot = auth()->user()->currentLobbySlot;
    $lobby = $activeSlot ? $activeSlot->lobby : null;
    $lobbyActive = $lobby && in_array($lobby->status, ['searching', 'confirming']);
@endphp

<section class="ml-3 relative" 
     x-data="{ 
         open: false, 
         unreadCount: {{ $count }},
         
         init() {
             setInterval(() => {
                 this.checkNotifications();
             }, 15000);
         },

         checkNotifications() {
             fetch('{{ route('notifications.checkNew') }}')
                 .then(response => response.json())
                 .then(data => {
                     this.unreadCount = data.count;
                     const container = document.getElementById('notification-list-container');
                     if(container) container.innerHTML = data.html;
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

    {{-- BOTÓN DE ACTIVACIÓN (CAMPANA O RADAR) --}}
    <button @click="open = ! open" id="tour-notificaciones" class="relative p-2 rounded-full text-gray-400 hover:text-white focus:outline-none transition-all duration-300">
        <span class="sr-only">Notificaciones</span>
        
        @if($lobbyActive)
             <svg class="h-6 w-6 text-green-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        @else
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        @endif
        
        {{-- INDICADORES VISUALES --}}
        <span x-show="unreadCount > 0" 
              class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-red-500 animate-pulse"></span>
        
        @if($lobbyActive)
            <span x-show="unreadCount === 0" class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-green-500 animate-ping"></span>
        @endif
    </button>

    {{-- PANEL DESPLEGABLE --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false" 
         class="absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-96 origin-top-right rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black/5 z-50 overflow-hidden"
         style="display: none;">
        
        {{-- ENCABEZADO --}}
        <header class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center gap-2">
                <h3 class="font-black text-xs tracking-widest text-gray-800 dark:text-gray-100 uppercase">Alertas</h3>
                <span x-show="unreadCount > 0" 
                      class="text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-md font-bold"
                      x-text="unreadCount">
                </span>
            </div>
            
            <form x-show="unreadCount > 0" action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition uppercase tracking-tighter italic">
                    Limpiar todo
                </button>
            </form>
        </header>

        {{-- CONTENEDOR DE LISTA --}}
        {{-- Aquí se cargará 'notifications-list.blade.php', el cual ya incluye la tarjeta de Matchmaking --}}
        <div class="max-h-[70vh] sm:max-h-[28rem] overflow-y-auto custom-scrollbar" id="notification-list-container">
            @include('navigation.partials.notifications-list')
        </div>

        {{-- PIE DE PÁGINA --}}
        <footer class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('notifications.index') }}" class="block w-full py-2.5 text-center text-[10px] font-black text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors uppercase tracking-[0.2em]">
                Historial Completo
            </a>
        </footer>
    </div>
</section>

{{-- LÓGICA DE TIEMPOS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(() => {
            const timerElements = document.querySelectorAll('.notif-timer');
            const now = new Date().getTime();
            timerElements.forEach(el => {
                const expiry = parseInt(el.getAttribute('data-expiry'));
                const distance = expiry - now;
                if (distance < 0) {
                    el.innerHTML = "EXPIRADO";
                    el.classList.add('text-red-600'); 
                    return;
                }
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                el.innerHTML = `${minutes < 10 ? '0'+minutes : minutes}:${seconds < 10 ? '0'+seconds : seconds}`;
            });
        }, 1000);
    });
</script>