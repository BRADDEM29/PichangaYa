<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reservas\index.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Reservas - Administrador') }}
        </h2>
    </x-slot>

    {{-- Estilos para el reloj digital --}}
    <style>
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Botón Volver --}}
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="text-indigo-600 font-bold hover:underline">
                    &larr; Volver a la lista de canchas
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-700">
                        Reservas para: <span class="text-indigo-600">{{ $cancha->name }}</span>
                    </h3>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($reservas->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-lg text-gray-500 font-medium">Esta cancha no tiene reservas registradas aún.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registrado el</th>
                                    
                                    {{-- 🟢 COLUMNA NUEVA: TIEMPO RESTANTE --}}
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tiempo Restante</th>

                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario Cancha</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Pago</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- 
                                    🟢 ORDENAMIENTO PERSONALIZADO
                                    Primero las pendientes, luego las demás.
                                --}}
                                @php
                                    $sortedReservas = $reservas->sortByDesc(function($reserva) {
                                        return $reserva->status === 'pending' ? 1 : 0;
                                    });
                                @endphp

                                @foreach ($sortedReservas as $reserva)
                                    <tr class="transition-colors {{ $reserva->status === 'pending' ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-gray-50' }}">
                                        
                                        {{-- CLIENTE --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ $reserva->user->profile_photo_url }}" alt="{{ $reserva->user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $reserva->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $reserva->user->phone ?? 'Sin teléfono' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- FECHA REGISTRO --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ $reserva->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $reserva->created_at->format('H:i:s') }}
                                                <span class="text-gray-400 block text-[10px]">
                                                    ({{ $reserva->created_at->diffForHumans() }})
                                                </span>
                                            </div>
                                        </td>

                                        {{-- 🟢 TEMPORIZADOR (Solo si está pendiente) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reserva->status === 'pending')
                                                @php
                                                    // Calculamos expiración (10 min después de creación)
                                                    $expiry = $reserva->created_at->addMinutes(10)->timestamp * 1000;
                                                @endphp
                                                <div class="flex items-center gap-2">
                                                    <span class="animate-pulse text-red-500 text-xs">●</span>
                                                    <span class="font-digital text-lg font-bold text-red-600 admin-timer" 
                                                          data-expiry="{{ $expiry }}">
                                                        --:--
                                                    </span>
                                                </div>
                                                <span class="text-[10px] text-gray-500 block mt-1">Auto-cancela pronto</span>
                                            @elseif($reserva->status === 'cancelled')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">EXPIRADO</span>
                                            @else
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">COMPLETADO</span>
                                            @endif
                                        </td>

                                        {{-- HORARIO CANCHA --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-bold">
                                                {{ $reserva->start_time->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $reserva->start_time->format('H:i') }} - {{ $reserva->end_time->format('H:i') }}
                                            </div>
                                        </td>

                                        {{-- PRECIO --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </td>

                                        {{-- ESTADO DE PAGO --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reserva->status !== 'cancelled')
                                                <form action="{{ route('admin.reservas.updateStatus', $reserva) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @php
                                                        $borderColor = match($reserva->status) {
                                                            'pending' => 'border-gray-300',
                                                            'advance_paid' => 'border-yellow-400 bg-yellow-50 text-yellow-800', 
                                                            'fully_paid' => 'border-green-500 bg-green-50 text-green-800',
                                                            default => 'border-gray-300'
                                                        };
                                                    @endphp

                                                    <select name="status" onchange="this.form.submit()" 
                                                            class="block w-full pl-3 pr-8 py-2 text-xs font-bold border-2 {{ $borderColor }} rounded-md leading-5 shadow-sm focus:outline-none focus:border-indigo-300 transition duration-150 ease-in-out cursor-pointer">
                                                        
                                                        <option value="pending" {{ $reserva->status == 'pending' ? 'selected' : '' }}>
                                                            ⚪ Pendiente
                                                        </option>
                                                        <option value="advance_paid" {{ $reserva->status == 'advance_paid' ? 'selected' : '' }}>
                                                            🟡 Adelanto Pagado
                                                        </option>
                                                        <option value="fully_paid" {{ $reserva->status == 'fully_paid' ? 'selected' : '' }}>
                                                            🟢 Pago Completo
                                                        </option>
                                                    </select>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    🚫 Cancelada
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($reserva->status !== 'cancelled')
                                                <form action="{{ route('admin.reservas.updateStatus', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    
                                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline font-semibold text-xs flex items-center justify-end w-full">
                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        Cancelar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
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

    {{-- 🟢 SCRIPT DEL RELOJ (Igual al de las notificaciones) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerElements = document.querySelectorAll('.admin-timer');
            
            if(timerElements.length === 0) return;

            function updateAdminTimers() {
                const now = new Date().getTime();
                
                timerElements.forEach(el => {
                    const expiry = parseInt(el.getAttribute('data-expiry'));
                    const distance = expiry - now;
                    
                    if (distance < 0) {
                        el.innerHTML = "EXPIRADO";
                        el.classList.remove('text-red-600');
                        el.classList.add('text-gray-500'); 
                        // Opcional: Recargar la página si quieres que se actualice el estado visualmente
                        // location.reload(); 
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    const formattedMin = minutes < 10 ? '0' + minutes : minutes;
                    const formattedSec = seconds < 10 ? '0' + seconds : seconds;
                    
                    el.innerHTML = `${formattedMin}:${formattedSec}`;
                });
            }

            setInterval(updateAdminTimers, 1000);
            updateAdminTimers(); 
        });
    </script>
</x-app-layout>