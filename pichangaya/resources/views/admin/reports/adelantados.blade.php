<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\adelantados.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">← Volver</a>
            <h2 class="font-semibold text-xl text-blue-700 leading-tight">💎 Reporte de Pagos Adelantados</h2>
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
                    <span class="text-4xl">⚡</span>
                </div>
                <div class="bg-white shadow rounded-xl p-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Adelantos por Cancha</h4>
                    <div class="h-32"><canvas id="chartAdvance"></canvas></div>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                    <h3 class="font-bold text-blue-800">Reservas con Pago Adelantado</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($adelantados as $reserva)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700">{{ $reserva->cancha->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-black rounded-full bg-blue-100 text-blue-800">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay pagos adelantados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById('chartAdvance'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($advanceByCancha)) !!},
                    datasets: [{
                        label: 'Adelantos (S/)',
                        data: {!! json_encode(array_values($advanceByCancha)) !!},
                        backgroundColor: '#3B82F6', borderRadius: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { display: false } } }
            });
        });
    </script>
</x-app-layout>