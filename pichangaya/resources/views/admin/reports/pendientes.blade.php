<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\pendientes.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">← Volver</a>
            <h2 class="font-semibold text-xl text-yellow-700 leading-tight">⚠️ Reporte de Reservas Pendientes</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- RESUMEN SUPERIOR --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-yellow-700 uppercase">Monto Total por Cobrar</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalPendiente, 2) }}</p>
                    </div>
                    <span class="text-4xl">⏳</span>
                </div>
                {{-- GRÁFICO: DONDE ESTÁ EL DINERO PENDIENTE --}}
                <div class="bg-white shadow rounded-xl p-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Dinero pendiente por Cancha</h4>
                    <div class="h-32"><canvas id="chartPending"></canvas></div>
                </div>
            </div>

            {{-- TABLA DETALLADA --}}
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50">
                    <h3 class="font-bold text-yellow-800">Listado de Reservas Pendientes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha / Dueño</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Monto Esperado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pendientes as $reserva)
                                <tr class="hover:bg-yellow-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-indigo-600">{{ $reserva->cancha->name }}</div>
                                        <div class="text-xs text-gray-500">Dueño: {{ $reserva->cancha->user->name ?? 'Desconocido' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-black rounded-full bg-yellow-100 text-yellow-800">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">¡Genial! No hay cobros pendientes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById('chartPending'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($pendingByCancha)) !!},
                    datasets: [{
                        label: 'Monto Pendiente (S/)',
                        data: {!! json_encode(array_values($pendingByCancha)) !!},
                        backgroundColor: '#EAB308', borderRadius: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { display: false } } }
            });
        });
    </script>
</x-app-layout>