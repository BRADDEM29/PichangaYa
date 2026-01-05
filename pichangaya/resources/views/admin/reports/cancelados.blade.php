<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\cancelados.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">← Volver</a>
            <h2 class="font-semibold text-xl text-red-700 leading-tight">Reporte de Reservas Canceladas</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- RESUMEN SUPERIOR --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-red-700 uppercase">Ingreso Perdido Total</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalPerdido, 2) }}</p>
                    </div>
                    <span class="text-4xl"></span>
                </div>
                <div class="bg-white shadow rounded-xl p-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Pérdida por Distrito</h4>
                    <div class="h-32"><canvas id="chartLost"></canvas></div>
                </div>
            </div>

            {{-- TABLA DETALLADA --}}
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-red-50">
                    <h3 class="font-bold text-red-800">Historial de Cancelaciones</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha Cancelación</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha / Dueño</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Monto Perdido</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($cancelados as $reserva)
                                <tr class="hover:bg-red-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800">{{ $reserva->updated_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">Original: {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700">{{ $reserva->cancha->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reserva->cancha->district->name ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-black rounded-full bg-red-100 text-red-800">
                                            - S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay reservas canceladas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById('chartLost'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($lostByDistrict)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($lostByDistrict)) !!},
                        backgroundColor: ['#EF4444', '#F87171', '#FCA5A5'], borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });
        });
    </script>
</x-app-layout>