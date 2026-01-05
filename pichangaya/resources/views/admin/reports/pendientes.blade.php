<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-400 transition rounded-full hover:bg-gray-100 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            </a>
            <h2 class="text-xl font-semibold leading-tight text-amber-700">⚠️ Reporte de Reservas Pendientes</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- RESUMEN SUPERIOR --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="flex items-center justify-between p-6 border border-amber-200 bg-amber-50 rounded-2xl">
                    <div>
                        <p class="text-sm font-bold tracking-wider text-amber-700 uppercase">Monto Total por Cobrar</p>
                        <p class="mt-2 text-4xl font-black text-gray-800">S/ {{ number_format($totalPendiente, 2) }}</p>
                    </div>
                    <div class="p-4 bg-white rounded-full shadow-sm text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <div class="p-5 bg-white shadow rounded-2xl border border-gray-100" x-data="{ init() { new Chart(document.getElementById('chartPending'), { type: 'bar', data: { labels: @js(array_keys($pendingByCancha)), datasets: [{ label: 'Pendiente (S/)', data: @js(array_values($pendingByCancha)), backgroundColor: '#F59E0B', borderRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: {display:false} }, x: { display: false } } } }); } }">
                    <h4 class="mb-3 text-xs font-bold text-gray-400 uppercase">Dinero pendiente por Cancha</h4>
                    <div class="h-32"><canvas id="chartPending"></canvas></div>
                </div>
            </div>

            {{-- TABLA INTERACTIVA --}}
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl">
                <div class="px-6 py-4 border-b border-gray-100 bg-amber-50">
                    <h3 class="font-bold text-amber-800">Listado de Cobros Pendientes (Click para gestionar)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Fecha de Juego (Entrada)</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Cancha / Dueño</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-right text-gray-500 uppercase">Monto Esperado</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pendientes as $reserva)
                                {{-- FILA CLICABLE: Redirige a la vista de reservas de esa cancha --}}
                                <tr class="transition cursor-pointer group hover:bg-amber-50"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-gray-100 rounded text-gray-500 group-hover:bg-amber-100 group-hover:text-amber-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                {{-- FECHA DE ENTRADA (JUEGO) --}}
                                                <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-indigo-600 group-hover:underline">{{ $reserva->cancha->name }}</div>
                                        <div class="text-xs text-gray-500">Dueño: {{ $reserva->cancha->user->name ?? 'Desconocido' }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-sm font-black rounded-full bg-amber-100 text-amber-800">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto group-hover:text-amber-600 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">¡Genial! No hay cobros pendientes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>