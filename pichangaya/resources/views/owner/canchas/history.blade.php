<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Historial: ') . $cancha->name }}
            </h2>
            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-gray-600 transition">
                ⬅ Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            @if($reservasPorMes->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 text-center">
                    <p class="text-gray-500 text-xl">🏟️ Esta cancha aún no tiene reservas registradas.</p>
                </div>
            @else
                
                {{-- BUCLE PRINCIPAL: Recorre cada MES --}}
                @foreach($reservasPorMes as $mes => $reservas)
                    
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-t-4 border-indigo-500">
                        
                        {{-- CABECERA DEL MES --}}
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-indigo-700 capitalize">{{ $mes }}</h3>
                            <span class="text-sm text-gray-500">{{ count($reservas) }} reservas</span>
                        </div>

                        {{-- TABLA --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Día</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Pago</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Costo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $totalMes = 0; @endphp
                                    
                                    @foreach($reservas as $reserva)
                                        @php 
                                            // Solo sumamos al total si NO está cancelada
                                            if($reserva->status !== 'cancelled') {
                                                $totalMes += $reserva->total_price;
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                                {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'Usuario Eliminado' }}</div>
                                                <div class="text-xs text-gray-500">{{ $reserva->user->email ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}
                                            </td>
                                            
                                            {{-- 🟢 ESTADO CON COLORES Y TRADUCCIÓN --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $statusConfig = [
                                                        'pending'      => ['label' => 'Pendiente',       'class' => 'bg-gray-100 text-gray-800'],
                                                        'confirmed'    => ['label' => 'Confirmada',      'class' => 'bg-blue-100 text-blue-800'],
                                                        'advance_paid' => ['label' => 'Adelanto Pagado', 'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200'],
                                                        'fully_paid'   => ['label' => 'Pago Completo',   'class' => 'bg-green-100 text-green-800 border border-green-200'],
                                                        'cancelled'    => ['label' => 'Cancelada',       'class' => 'bg-red-100 text-red-800'],
                                                    ];
                                                    
                                                    $currentStatus = $statusConfig[$reserva->status] ?? ['label' => ucfirst($reserva->status), 'class' => 'bg-gray-100'];
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $currentStatus['class'] }}">
                                                    {{ $currentStatus['label'] }}
                                                </span>
                                            </td>

                                            {{-- 🟢 COSTO: TACHADO SI ES CANCELADO --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if($reserva->status === 'cancelled')
                                                    <span class="text-gray-400 line-through decoration-red-500 decoration-2">
                                                        S/ {{ number_format($reserva->total_price, 2) }}
                                                    </span>
                                                    <span class="text-xs text-red-500 block">(Anulado)</span>
                                                @else
                                                    <span class="text-gray-900 font-bold">
                                                        S/ {{ number_format($reserva->total_price, 2) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- FILA TOTAL DEL MES --}}
                                    <tr class="bg-indigo-50 border-t-2 border-indigo-100">
                                        <td colspan="4" class="px-6 py-4 text-right font-bold text-indigo-800 uppercase text-sm">
                                            Total Ingresos {{ $mes }}:
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-indigo-700 text-lg">
                                            S/ {{ number_format($totalMes, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </div>
</x-app-layout>