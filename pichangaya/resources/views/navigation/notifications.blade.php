{{-- resources/views/navigation/notifications.blade.php --}}

@php
    // Lógica inicial para la primera carga
    $allNotifications = auth()->user()->unreadNotifications;
    $filteredNotifications = $allNotifications->filter(function ($notification) {
        if (isset($notification->data['reserva_id'])) {
            $reserva = \App\Models\Reserva::find($notification->data['reserva_id']);
            if (!$reserva || $reserva->status !== 'pending') return false; 
        }
        return true;
    });
    $count = $filteredNotifications->count();
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
                     // Actualizar el número rojo
                     this.unreadCount = data.count;
                     // Inyectar el nuevo HTML de la lista
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

    <button @click="open = ! open" id="tour-notificaciones" class="relative p-1 rounded-full text-gray-400 hover:text-white focus:outline-none transition-colors">
        <span class="sr-only">Notificaciones</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        {{-- PUNTO ROJO (Reactivo) --}}
        <span x-show="unreadCount > 0 || {{ $alertEmail ? 'true' : 'false' }} || {{ $alertPhone ? 'true' : 'false' }}" 
              x-transition.opacity
              class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-red-500 animate-pulse"></span>
    </button>

    <div x-show="open" @click.away="open = false" style="display: none;"
            class="origin-top-right absolute right-0 mt-2 w-80 sm:w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-t-md">
            <div class="flex items-center gap-2">
                <span class="font-black text-gray-800 dark:text-gray-100">ALERTAS</span>
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

        <div class="max-h-[25rem] overflow-y-auto" id="notification-list-container">
            {{-- 🟢 AQUÍ INCLUIMOS EL PARTIAL (Primera Carga) --}}
            @include('navigation.partials.notifications-list')
        </div>

        <div class="block bg-gray-50 dark:bg-gray-700 text-center px-4 py-2 border-t border-gray-100 dark:border-gray-600 rounded-b-md">
            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 w-full block uppercase tracking-wide">Ver historial completo</a>
        </div>
    </div>
</div>

{{-- SCRIPT DE TEMPORIZADORES (Global) --}}
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