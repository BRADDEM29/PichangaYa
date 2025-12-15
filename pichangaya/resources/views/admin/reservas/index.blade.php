<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Reservas - Administrador') }}
        </h2>
    </x-slot>

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
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Pago</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reservas as $reserva)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        
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

                                        {{-- DETALLES (HORARIO) --}}
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

                                        {{-- ESTADO DE PAGO (SELECTOR ADMIN) --}}
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

                                        {{-- ACCIONES (CANCELAR) --}}
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
</x-app-layout>