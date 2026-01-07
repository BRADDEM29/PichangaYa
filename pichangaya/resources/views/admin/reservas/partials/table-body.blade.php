{{-- resources/views/admin/reservas/partials/table-body.blade.php --}}

@php
    // Ordenamiento: Pendientes primero
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

        {{-- TEMPORIZADOR --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($reserva->status === 'pending')
                @php
                    $expiry = $reserva->created_at->addMinutes(10)->timestamp * 1000;
                @endphp
                <div class="flex items-center gap-2">
                    {{-- SVG Circle en lugar de emoji --}}
                    <svg class="w-3 h-3 text-red-500 animate-pulse fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                    <span class="font-digital text-lg font-bold text-red-600 admin-timer" 
                          data-expiry="{{ $expiry }}">
                        --:--
                    </span>
                </div>
                <span class="text-[10px] text-gray-500 block mt-1">Auto-cancela pronto</span>
            @elseif($reserva->status === 'cancelled')
                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">EXPIRADO/CANCELADO</span>
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

        {{-- PRECIO TOTAL --}}
        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
            S/ {{ number_format($reserva->total_price, 2) }}
        </td>

        {{-- NUEVA COLUMNA: ADELANTO (20%) --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-blue-600">
                S/ {{ number_format($reserva->total_price * 0.20, 2) }}
            </div>
            <span class="text-[10px] text-blue-400">Requerido</span>
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
                        <option value="pending" {{ $reserva->status == 'pending' ? 'selected' : '' }}>⚪ Pendiente</option>
                        <option value="advance_paid" {{ $reserva->status == 'advance_paid' ? 'selected' : '' }}>🟡 Adelanto Pagado</option>
                        <option value="fully_paid" {{ $reserva->status == 'fully_paid' ? 'selected' : '' }}>🟢 Pago Completo</option>
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
                        Cancelar
                    </button>
                </form>
            @else
                <span class="text-gray-400 text-xs">-</span>
            @endif
        </td>
    </tr>
@endforeach