<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Reservas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 🟢 NOTIFICACIÓN FLOTANTE / TEMPORIZADOR --}}
            @foreach($reservas as $reserva)
                @if($reserva->status == 'pending' && $reserva->created_at->diffInMinutes(now()) < 10)
                    <div id="floating-timer-{{ $reserva->id }}" class="fixed bottom-4 right-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-2xl z-50 animate-bounce-slow" style="animation: bounce 2s infinite;">
                        <p class="font-bold">⚠️ ¡Acción Requerida!</p>
                        <p>Reserva en: {{ $reserva->cancha->name }}</p>
                        <p class="text-sm">Tienes <span id="timer-{{ $reserva->id }}" class="font-black text-red-600 text-lg">Calculating...</span> para confirmar/pagar.</p>
                        <p class="text-xs mt-1">Si llega a 00:00, se cancelará automáticamente.</p>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Fecha de creación + 10 minutos
                            const deadline = new Date("{{ $reserva->created_at->addMinutes(10)->toIso8601String() }}").getTime();
                            
                            const x = setInterval(function() {
                                const now = new Date().getTime();
                                const distance = deadline - now;
                                
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                
                                document.getElementById("timer-{{ $reserva->id }}").innerHTML = 
                                    (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
                                
                                if (distance < 0) {
                                    clearInterval(x);
                                    document.getElementById("timer-{{ $reserva->id }}").innerHTML = "EXPIRADO";
                                    document.getElementById("floating-timer-{{ $reserva->id }}").classList.add('opacity-50');
                                    // Opcional: Recargar la página para ver que cambió a 'cancelled'
                                    // location.reload();
                                }
                            }, 1000);
                        });
                    </script>
                @endif
            @endforeach

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-700 mb-6">Mis Partidos Programados</h3>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($reservas->isEmpty())
                    <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-xl text-gray-500 mb-2">Aún no tienes reservas activas.</p>
                        <a href="{{ route('dashboard') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                            ⚽ Buscar Cancha
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cancha</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reservas as $reserva)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $reserva->cancha->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            {{ $reserva->start_time->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            {{ $reserva->start_time->format('h:i A') }} - {{ $reserva->end_time->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-indigo-600">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reserva->status == 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold flex items-center gap-1">
                                                    ⏳ Pendiente (10 min)
                                                </span>
                                            @elseif($reserva->status == 'cancelled')
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Cancelada</span>
                                            @else
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Confirmada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($reserva->status !== 'cancelled' && $reserva->start_time > now())
                                                {{-- Botón Cancelar --}}
                                                <form action="{{ route('reservas.cancel', $reserva) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                                        Cancelar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 italic">No disponible</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $reservas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>