{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\history.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <hgroup class="flex items-center justify-between">
            <nav class="flex items-center gap-4">
                {{-- Botón Volver --}}
                <a href="{{ route('owner.canchas.index') }}" 
                   class="flex items-center justify-center w-10 h-10 text-gray-500 transition bg-white rounded-full shadow-sm hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 border border-gray-200"
                   title="Volver al listado">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                
                <hgroup>
                    <h2 class="text-xl font-bold leading-tight text-gray-800">
                        {{ $cancha->name }}
                    </h2>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-gray-400">
                            <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Historial de Reservas
                    </p>
                </hgroup>
            </nav>
            
            {{-- Badge decorativo --}}
            <span class="hidden sm:flex px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100">
                Vista Administrativa
            </span>
        </hgroup>
    </x-slot>

    <main class="py-12 bg-gray-50 min-h-screen">
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            @if($reservasPorMes->isEmpty())
                {{-- EMPTY STATE --}}
                <article class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-12 text-center border border-gray-100">
                    <figure class="mx-auto w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                    </figure>
                    <h3 class="text-lg font-bold text-gray-900">Sin historial de reservas</h3>
                    <p class="text-gray-500 mt-1">Esta cancha aún no tiene movimientos registrados.</p>
                </article>
            @else
                
                @foreach($reservasPorMes as $mes => $reservas)
                    <article class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100 ring-1 ring-black/5">
                        
                        {{-- CABECERA DEL MES --}}
                        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex justify-between items-center">
                            <hgroup class="flex items-center gap-3">
                                <figure class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </figure>
                                <h3 class="text-lg font-bold text-gray-800 capitalize tracking-wide">{{ $mes }}</h3>
                            </hgroup>
                            <span class="px-3 py-1 text-xs font-semibold text-gray-500 bg-gray-100 rounded-full border border-gray-200">
                                {{ count($reservas) }} reservas
                            </span>
                        </header>

                        {{-- TABLA --}}
                        <figure class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Día</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Horario</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Costo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @php $totalMes = 0; @endphp
                                    
                                    @foreach($reservas as $reserva)
                                        @php 
                                            if($reserva->status !== 'cancelled') {
                                                $totalMes += $reserva->total_price;
                                            }
                                        @endphp
                                        <tr class="hover:bg-indigo-50/30 transition duration-150 group">
                                            
                                            {{-- FECHA --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <time class="flex flex-col" datetime="{{ $reserva->start_time }}">
                                                    <span class="text-sm font-bold text-gray-700">
                                                        {{ \Carbon\Carbon::parse($reserva->start_time)->format('d') }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 uppercase font-bold">
                                                        {{ \Carbon\Carbon::parse($reserva->start_time)->isoFormat('ddd') }}
                                                    </span>
                                                </time>
                                            </td>

                                            {{-- CLIENTE --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <article class="flex items-center gap-3">
                                                    <figure class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 flex items-center justify-center text-xs font-black border border-indigo-200">
                                                        {{ substr($reserva->user->name ?? '?', 0, 1) }}
                                                    </figure>
                                                    <hgroup>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $reserva->user->name ?? 'Usuario Eliminado' }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $reserva->user->email ?? '' }}
                                                        </div>
                                                    </hgroup>
                                                </article>
                                            </td>

                                            {{-- HORARIO --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <time class="flex items-center gap-1.5 text-sm text-gray-600 bg-gray-50 px-2 py-1 rounded-md w-fit border border-gray-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}
                                                </time>
                                            </td>
                                            
                                            {{-- ESTADO --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $statusConfig = [
                                                        'pending'      => ['label' => 'Pendiente',       'class' => 'bg-gray-100 text-gray-600 ring-gray-500/10'],
                                                        'confirmed'    => ['label' => 'Confirmada',      'class' => 'bg-blue-50 text-blue-700 ring-blue-700/10'],
                                                        'advance_paid' => ['label' => 'Adelanto',        'class' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
                                                        'fully_paid'   => ['label' => 'Pagado',          'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                                                        'cancelled'    => ['label' => 'Cancelada',       'class' => 'bg-red-50 text-red-700 ring-red-600/10'],
                                                    ];
                                                    
                                                    $currentStatus = $statusConfig[$reserva->status] ?? ['label' => ucfirst($reserva->status), 'class' => 'bg-gray-100 text-gray-600'];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $currentStatus['class'] }}">
                                                    {{ $currentStatus['label'] }}
                                                </span>
                                            </td>

                                            {{-- COSTO --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                @if($reserva->status === 'cancelled')
                                                    <hgroup class="flex flex-col items-end">
                                                        <data class="text-sm text-gray-400 line-through decoration-red-400 decoration-2" value="{{ $reserva->total_price }}">
                                                            S/ {{ number_format($reserva->total_price, 2) }}
                                                        </data>
                                                        <span class="text-[10px] text-red-500 font-medium bg-red-50 px-1 rounded">Anulado</span>
                                                    </hgroup>
                                                @else
                                                    <data class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition" value="{{ $reserva->total_price }}">
                                                        S/ {{ number_format($reserva->total_price, 2) }}
                                                    </data>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- TOTAL DEL MES --}}
                                    <tr class="bg-indigo-50/50 border-t border-indigo-100">
                                        <td colspan="4" class="px-6 py-4 text-right">
                                            <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">
                                                Total Ingresos {{ $mes }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <data class="font-black text-indigo-700 text-lg" value="{{ $totalMes }}">
                                                S/ {{ number_format($totalMes, 2) }}
                                            </data>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </figure>
                    </article>
                @endforeach

            @endif
        </section>
    </main>

    <footer class="relative z-10">
        <x-footer />
    </footer>
</x-app-layout>