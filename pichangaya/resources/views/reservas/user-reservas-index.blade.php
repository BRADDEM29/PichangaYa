<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Reservas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <a href="{{ route('dashboard') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
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
                                        
                                        {{-- 🟢 ESTADO TRADUCIDO Y CON COLORES --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending'      => ['label' => 'Pendiente',       'class' => 'bg-gray-100 text-gray-800'],
                                                    'confirmed'    => ['label' => 'Confirmada',      'class' => 'bg-blue-100 text-blue-800'],
                                                    'advance_paid' => ['label' => 'Adelanto Pagado', 'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200'],
                                                    'fully_paid'   => ['label' => 'Pago Completo',   'class' => 'bg-green-100 text-green-800 border border-green-200'],
                                                    'cancelled'    => ['label' => 'Cancelada',       'class' => 'bg-red-100 text-red-800'],
                                                ];
                                                
                                                $currentStatus = $statusConfig[$reserva->status] ?? ['label' => $reserva->status, 'class' => 'bg-gray-100'];
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $currentStatus['class'] }}">
                                                {{ $currentStatus['label'] }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($reserva->status !== 'cancelled' && $reserva->start_time > now())
                                                
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('reservas.edit', $reserva) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-bold inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                                    Editar
                                                </a>

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