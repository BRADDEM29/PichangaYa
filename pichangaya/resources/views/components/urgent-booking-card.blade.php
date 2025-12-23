@auth
    @php
        // 1. Buscamos la reserva pendiente (últimos 10 min)
        $reservaPendiente = Auth::user()->reservas()
            ->with('cancha') // Cargamos datos de la cancha
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(10)) 
            ->latest()
            ->first();
            
        $whatsappUrl = "#";

        if($reservaPendiente) {
            // --- FECHAS Y HORAS ---
            $inicio = \Carbon\Carbon::parse($reservaPendiente->start_time);
            $fin    = \Carbon\Carbon::parse($reservaPendiente->end_time);
            
            // Calculamos duración en horas (ej: 1.5 horas)
            $horas  = $fin->diffInMinutes($inicio) / 60; 

            // --- LÓGICA DE PRECIO CORREGIDA (Según tu ReservaController) ---
            // 1. Intentamos obtener el precio por hora de la cancha (price_per_hour)
            $precioHora = $reservaPendiente->cancha->price_per_hour ?? 0;

            // 2. Usamos 'total_price' que es como lo guardas en la BD.
            // Si por alguna razón es 0, lo calculamos manualmente.
            $montoTotal = $reservaPendiente->total_price > 0 
                            ? $reservaPendiente->total_price 
                            : ($precioHora * $horas);

            // --- TEXTO DEL MENSAJE (Emoji safe) ---
            // Usamos 'name' para la cancha según tu CanchaController
            $nombreCancha = $reservaPendiente->cancha->name ?? 'Cancha #'.$reservaPendiente->cancha_id;
            
            $mensaje  = "Hola, 🚨 *URGENTE* - Envío comprobante para evitar cancelación y STRIKE.\n\n";
            $mensaje .= "📄 *Reserva:* #" . $reservaPendiente->id . "\n";
            $mensaje .= "⚽ *Cancha:* " . $nombreCancha . "\n";
            $mensaje .= "📆 *Fecha:* " . $inicio->format('d/m/Y') . "\n";
            $mensaje .= "⏰ *Horario:* " . $inicio->format('h:i A') . " - " . $fin->format('h:i A') . " (" . $horas . "h)\n";
            $mensaje .= "💰 *Total a Pagar:* S/ " . number_format($montoTotal, 2) . "\n";
            $mensaje .= "👤 *Usuario:* " . Auth::user()->name;

            // --- GENERAR LINK SEGURO ---
            $whatsappUrl = "https://wa.me/51940766968?" . http_build_query(['text' => $mensaje]);
        }
    @endphp

    @if($reservaPendiente)
        {{-- Tarjeta Flotante Saltarina --}}
        <div id="urgent-card" class="fixed bottom-5 right-5 z-50 max-w-sm w-full animate-[bounce_2s_infinite] hover:animate-none group">
            
            <div class="bg-white border-l-8 border-red-600 rounded-lg shadow-2xl overflow-hidden relative">
                
                {{-- Botón Cerrar --}}
                <button onclick="document.getElementById('urgent-card').remove()" class="absolute top-1 right-2 text-gray-400 hover:text-gray-600">
                    &times;
                </button>

                <div class="p-4 flex flex-col gap-3">
                    
                    {{-- Encabezado --}}
                    <div class="flex items-start gap-3">
                        <div class="bg-red-100 p-2 rounded-full shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-red-700 text-sm uppercase">⚠️ Acción Requerida</h3>
                            <div class="text-xs text-gray-600 mt-1 leading-relaxed">
                                Reserva <strong>#{{ $reservaPendiente->id }}</strong> pendiente de pago.
                                <br>
                                <span class="text-red-600 font-semibold block mt-1">
                                    Si el tiempo expira, se cancelará y recibirás una falta (Strike).
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Cronómetro --}}
                    <div class="bg-gray-900 rounded text-center py-2 relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-900 opacity-20 animate-pulse"></div>
                        <span class="relative z-10 text-xs text-gray-400 uppercase tracking-widest block mb-1">Tiempo restante</span>
                        <span id="card-timer" class="relative z-10 text-3xl font-mono font-bold text-yellow-400 tracking-widest">00:00</span>
                    </div>

                    {{-- Barra de Progreso --}}
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div id="card-progress" class="bg-red-500 h-full transition-all duration-1000 ease-linear" style="width: 100%"></div>
                    </div>

                    {{-- Botón WhatsApp --}}
                    <a href="{{ $whatsappUrl }}" 
                       target="_blank"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition transform hover:scale-105 active:scale-95">
                        <span class="flex items-center justify-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            ENVIAR COMPROBANTE
                        </span>
                    </a>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const createdAt = new Date("{{ $reservaPendiente->created_at }}").getTime();
                    const durationMinutes = 10;
                    const deadline = createdAt + (durationMinutes * 60 * 1000); 
                    
                    const timerElement = document.getElementById('card-timer');
                    const progressBar = document.getElementById('card-progress');
                    const card = document.getElementById('urgent-card');

                    // Control de animación
                    setTimeout(() => {
                        if(card) {
                            card.classList.remove('animate-[bounce_2s_infinite]');
                            card.classList.add('animate-[bounce_3s_infinite]');
                        }
                    }, 3000);

                    function updateTimer() {
                        const now = new Date().getTime();
                        const distance = deadline - now;
                        const totalDuration = durationMinutes * 60 * 1000;

                        let percentage = (distance / totalDuration) * 100;
                        if(percentage < 0) percentage = 0;
                        if(progressBar) progressBar.style.width = percentage + "%";

                        if (distance < 0) {
                            if(timerElement) timerElement.innerHTML = "00:00";
                            if(card) {
                                card.style.transition = "opacity 0.5s ease-out";
                                card.style.opacity = '0';
                                setTimeout(() => card.remove(), 500);
                            }
                            return;
                        }

                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if(timerElement) {
                            timerElement.innerHTML = 
                                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                                (seconds < 10 ? "0" + seconds : seconds);
                        }
                    }

                    setInterval(updateTimer, 1000);
                    updateTimer();
                });
            </script>
        </div>
    @endif
@endauth