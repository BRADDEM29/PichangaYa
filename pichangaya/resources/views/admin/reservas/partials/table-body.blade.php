{{-- resources/views/admin/reservas/partials/table-body.blade.php --}}

@php
    // Ordenamiento: Pendientes primero para que aparezcan arriba
    $sortedReservas = $reservas->sortByDesc(function($reserva) {
        return $reserva->status === 'pending' ? 1 : 0;
    });
@endphp

@foreach ($sortedReservas as $reserva)
    <tr class="transition-colors {{ $reserva->status === 'pending' ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-gray-50' }}">
        
        {{-- 1. CLIENTE --}}
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

        {{-- 2. FECHA REGISTRO --}}
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

        {{-- 3. TEMPORIZADOR (Solo si está pendiente) --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($reserva->status === 'pending')
                @php
                    $expiry = $reserva->created_at->addMinutes(10)->timestamp * 1000;
                @endphp
                <div class="flex items-center gap-2">
                    {{-- Icono Reloj SVG Animado --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500 animate-pulse">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    
                    <span class="font-digital text-lg font-bold text-red-600 admin-timer" 
                          data-expiry="{{ $expiry }}">
                        --:--
                    </span>
                </div>
                <span class="text-[10px] text-gray-500 block mt-1">Auto-cancela pronto</span>
            @elseif($reserva->status === 'cancelled')
                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded shadow-sm">EXPIRADO/CANCELADO</span>
            @else
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded shadow-sm">COMPLETADO</span>
            @endif
        </td>

        {{-- 4. HORARIO CANCHA --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 font-bold">
                {{ $reserva->start_time->format('d/m/Y') }}
            </div>
            <div class="text-xs text-gray-500">
                {{ $reserva->start_time->format('H:i') }} - {{ $reserva->end_time->format('H:i') }}
            </div>
        </td>

        {{-- 5. PRECIO TOTAL --}}
        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
            S/ {{ number_format($reserva->total_price, 2) }}
        </td>

        {{-- 6. ADELANTO REQUERIDO (20%) --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-blue-600">
                S/ {{ number_format($reserva->total_price * 0.20, 2) }}
            </div>
            <span class="text-[10px] text-blue-400">Requerido</span>
        </td>

        {{-- 7. ESTADO DE PAGO (SELECTOR PERSONALIZADO CON SVG) --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($reserva->status !== 'cancelled')
                <form action="{{ route('admin.reservas.updateStatus', $reserva) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php
                        // Configuración dinámica de estilos e iconos según el estado
                        $config = match($reserva->status) {
                            'pending' => [
                                'clases' => 'border-gray-300 bg-white text-gray-600 focus:ring-gray-200',
                                // Icono Reloj (Outline)
                                'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />'
                            ],
                            'advance_paid' => [
                                'clases' => 'border-yellow-400 bg-yellow-50 text-yellow-800 focus:ring-yellow-200',
                                // Icono Billete/Pago Parcial
                                'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />'
                            ],
                            'fully_paid' => [
                                'clases' => 'border-green-500 bg-green-50 text-green-800 focus:ring-green-200',
                                // Icono Check/Verificado
                                'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                            ],
                            default => [
                                'clases' => 'border-gray-300', 
                                'icon' => ''
                            ]
                        };
                    @endphp

                    <div class="relative w-full">
                        {{-- 1. ICONO SVG (Posicionado Absolutamente a la izquierda) --}}
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                                 class="h-4 w-4 {{ $reserva->status === 'pending' ? 'text-gray-400' : 'text-current' }}">
                                {!! $config['icon'] !!}
                            </svg>
                        </div>

                        {{-- 2. EL SELECT (Con padding-left para dejar espacio al icono) --}}
                        <select name="status" onchange="this.form.submit()" 
                                class="block w-full rounded-md border-2 py-2 pl-8 pr-8 text-xs font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 cursor-pointer appearance-none transition ease-in-out duration-150 {{ $config['clases'] }}">
                            
                            {{-- Opciones con fondo blanco forzado para legibilidad --}}
                            <option value="pending" class="bg-white text-gray-900" {{ $reserva->status == 'pending' ? 'selected' : '' }}>
                                Pendiente
                            </option>
                            <option value="advance_paid" class="bg-white text-gray-900" {{ $reserva->status == 'advance_paid' ? 'selected' : '' }}>
                                Adelanto Pagado
                            </option>
                            <option value="fully_paid" class="bg-white text-gray-900" {{ $reserva->status == 'fully_paid' ? 'selected' : '' }}>
                                Pago Completo
                            </option>
                        </select>
                        
                        {{-- 3. FLECHITA PERSONALIZADA --}}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                </form>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 gap-1 shadow-sm">
                    {{-- Icono Ban/Prohibido --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    Cancelada
                </span>
            @endif
        </td>

        {{-- 8. ACCIONES (CANCELAR) --}}
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if ($reserva->status !== 'cancelled')
                <form action="{{ route('admin.reservas.updateStatus', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline font-semibold text-xs flex items-center justify-end w-full gap-1">
                        {{-- Icono X-Mark / Cancelar --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                </form>
            @else
                <span class="text-gray-400 text-xs">-</span>
            @endif
        </td>
    </tr>
@endforeach