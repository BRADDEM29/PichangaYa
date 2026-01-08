<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\reservas\index.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-600 p-2 rounded-lg text-white">
                {{-- NUEVO ICONO: Pig Money --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 11v.01" />
                    <path d="M5.173 8.378a3 3 0 1 1 4.656 -1.377" />
                    <path d="M16 4v3.803a6.019 6.019 0 0 1 2.658 3.197h1.341a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-1.342c-.336 .95 -.907 1.8 -1.658 2.473v2.027a1.5 1.5 0 0 1 -3 0v-.583a6.04 6.04 0 0 1 -1 .083h-4a6.04 6.04 0 0 1 -1 -.083v.583a1.5 1.5 0 0 1 -3 0v-2l0 -.027a6 6 0 0 1 4 -10.473h2.5l4.5 -3" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Cobros y Reservas') }}
            </h2>
        </div>
    </x-slot>

    {{-- Estilos para el reloj digital --}}
    <style>
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Control de Reservas</h3>
                    <p class="text-sm text-gray-500">Gestiona los pagos y tiempos de tus clientes.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">Operación exitosa</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200">
                @if ($reservas->isEmpty())
                    <div class="text-center py-20 px-6">
                        <div class="bg-gray-50 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Sin reservas activas</h3>
                        <p class="text-gray-500 mt-1 max-w-sm mx-auto">Aún no tienes reservas registradas en el sistema.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Tiempo Restante
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cancha / Horario</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Pago</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    $sortedReservas = $reservas->sortByDesc(function($reserva) {
                                        return $reserva->status === 'pending' ? 1 : 0;
                                    });
                                @endphp

                                @foreach ($sortedReservas as $reserva)
                                    <tr class="transition-colors group {{ $reserva->status === 'pending' ? 'bg-amber-50/50 hover:bg-amber-50' : 'hover:bg-gray-50' }}">
                                        
                                        {{-- CLIENTE --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" src="{{ $reserva->user->profile_photo_url }}" alt="{{ $reserva->user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $reserva->user->name }}</div>
                                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                        {{ $reserva->user->phone ?? 'Sin teléfono' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- TEMPORIZADOR --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reserva->status === 'pending')
                                                @php
                                                    $expiry = $reserva->created_at->addMinutes(10)->timestamp * 1000;
                                                @endphp
                                                <div class="flex items-center gap-2.5">
                                                    <span class="relative flex h-2.5 w-2.5">
                                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                                    </span>
                                                    <div class="flex flex-col">
                                                        <span class="font-digital text-lg font-bold text-red-600 owner-timer leading-none" 
                                                              data-expiry="{{ $expiry }}">
                                                            --:--
                                                        </span>
                                                        <span class="text-[10px] uppercase font-bold text-red-400 tracking-wide">Expira pronto</span>
                                                    </div>
                                                </div>
                                            @elseif($reserva->status === 'cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                                    EXPIRADO
                                                </span>
                                            @else
                                                <div class="flex items-center text-gray-400 text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $reserva->created_at->format('d/m H:i') }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- DETALLES (Corregido: Sin emojis) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-bold mb-1">{{ $reserva->cancha->name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-3">
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $reserva->start_time->format('d M') }}
                                                </span>
                                                <span class="text-gray-300">|</span>
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $reserva->start_time->format('H:i') }} - {{ $reserva->end_time->format('H:i') }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- TOTAL --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">S/ {{ number_format($reserva->total_price, 2) }}</div>
                                            @if($reserva->status == 'advance_paid')
                                                <div class="text-xs text-amber-600 font-medium">Resta: S/ {{ number_format($reserva->total_price / 2, 2) }}</div>
                                            @endif
                                        </td>

                                        {{-- ESTADO DE PAGO (SELECTOR - Sin emojis en options) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reserva->status !== 'cancelled')
                                                <form action="{{ route('owner.reservas.updateStatus', $reserva) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @php
                                                        $statusStyles = match($reserva->status) {
                                                            'pending' => 'border-gray-300 text-gray-600 focus:border-indigo-500 focus:ring-indigo-500',
                                                            'advance_paid' => 'border-amber-300 bg-amber-50 text-amber-700 font-bold focus:border-amber-500 focus:ring-amber-500', 
                                                            'fully_paid' => 'border-emerald-300 bg-emerald-50 text-emerald-700 font-bold focus:border-emerald-500 focus:ring-emerald-500',
                                                            default => 'border-gray-300'
                                                        };
                                                    @endphp

                                                    <div class="relative">
                                                        <select name="status" onchange="this.form.submit()" 
                                                                class="block w-full pl-3 pr-8 py-1.5 text-xs rounded-lg shadow-sm focus:ring-2 focus:ring-opacity-50 transition-colors cursor-pointer {{ $statusStyles }}">
                                                            
                                                            <option value="pending" {{ $reserva->status == 'pending' ? 'selected' : '' }}>
                                                                Pendiente
                                                            </option>
                                                            <option value="advance_paid" {{ $reserva->status == 'advance_paid' ? 'selected' : '' }}>
                                                                Adelanto Pagado
                                                            </option>
                                                            <option value="fully_paid" {{ $reserva->status == 'fully_paid' ? 'selected' : '' }}>
                                                                Pago Completo
                                                            </option>
                                                        </select>
                                                    </div>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Cancelada
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($reserva->status !== 'cancelled')
                                                <form action="{{ route('owner.reservas.updateStatus', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Cancelar Reserva">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $reservas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SCRIPT DEL RELOJ (Dueño) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerElements = document.querySelectorAll('.owner-timer');
            
            if(timerElements.length === 0) return;

            function updateOwnerTimers() {
                const now = new Date().getTime();
                
                timerElements.forEach(el => {
                    const expiry = parseInt(el.getAttribute('data-expiry'));
                    const distance = expiry - now;
                    
                    // Contenedor padre para quitar la animación del punto rojo
                    const container = el.closest('.flex'); 
                    const dot = container ? container.querySelector('.animate-ping') : null;
                    const dotStatic = container ? container.querySelector('.relative.inline-flex') : null;

                    if (distance < 0) {
                        el.innerHTML = "00:00";
                        el.classList.remove('text-red-600', 'font-bold');
                        el.classList.add('text-gray-400', 'font-medium'); 
                        
                        // Quitar animación de pulso si ya expiró
                        if(dot) dot.remove();
                        if(dotStatic) dotStatic.classList.replace('bg-red-500', 'bg-gray-300');
                        
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    const formattedMin = minutes < 10 ? '0' + minutes : minutes;
                    const formattedSec = seconds < 10 ? '0' + seconds : seconds;
                    
                    el.innerHTML = `${formattedMin}:${formattedSec}`;
                });
            }

            setInterval(updateOwnerTimers, 1000);
            updateOwnerTimers(); 
        });
    </script>
</x-app-layout>