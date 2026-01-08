<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">← Volver</a>
            <h2 class="font-semibold text-xl text-blue-700 leading-tight">Reporte de Pagos Adelantados</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-blue-700 uppercase">Monto en Adelantos</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalAdelanto, 2) }}</p>
                    </div>
                    <div class="p-4 bg-white rounded-full shadow-sm text-blue-600">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                </div>
                <div class="bg-white shadow rounded-xl p-4" x-data="{ init() { new Chart(document.getElementById('chartAdvance'), { type: 'bar', data: { labels: @js(array_keys($advanceByCancha)), datasets: [{ label: 'Adelantos (S/)', data: @js(array_values($advanceByCancha)), backgroundColor: '#3B82F6', borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: {display:false} }, x: { display: false } } } }); } }">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Adelantos por Cancha</h4>
                    <div class="h-32"><canvas id="chartAdvance"></canvas></div>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                    <h3 class="font-bold text-blue-800">Reservas con Pago Adelantado (Click para gestionar)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha de Juego (Entrada)</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Monto Total</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($adelantados as $reserva)
                                {{-- LINK A LA VISTA DE GESTIÓN --}}
                                <tr class="hover:bg-blue-50 transition cursor-pointer group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 bg-gray-100 rounded text-gray-500 group-hover:bg-blue-200 group-hover:text-blue-800 transition">
                                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700 group-hover:text-blue-700">{{ $reserva->cancha->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-black rounded-full bg-blue-100 text-blue-800">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto group-hover:text-blue-600 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay pagos adelantados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>