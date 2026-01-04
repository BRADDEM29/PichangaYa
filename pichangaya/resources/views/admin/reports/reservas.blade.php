<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\reservas.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte Detallado: Reservas y Horarios') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SECCIÓN DE GRÁFICOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- GRÁFICO 1: ESTADOS --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Distribución por Estados</h3>
                    <div class="h-64">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>

                {{-- GRÁFICO 2: HORARIOS --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Demanda por Horas (Tráfico)</h3>
                    <div class="h-64">
                        <canvas id="chartHourly"></canvas>
                    </div>
                </div>
            </div>

            {{-- 🟢 TABLA DE HISTORIAL COMPLETO --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <span class="mr-2 text-xl">📋</span> Historial Completo de Reservas (Incluye Usuarios Eliminados)
                    </h3>
                </div>

                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha y Hora</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($allReservas as $reserva)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm">
                                                <p class="font-bold text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</p>
                                                <p class="text-gray-500 text-xs">{{ $reserva->user->email ?? '' }}</p>
                                                @if($reserva->user && $reserva->user->trashed())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Eliminado</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $reserva->cancha->name ?? 'Cancha eliminada' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-gray-800">
                                        S/ {{ number_format($reserva->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        @php
                                            $statusColors = [
                                                'fully_paid'   => 'bg-green-100 text-green-800',
                                                'advance_paid' => 'bg-blue-100 text-blue-800',
                                                'pending'      => 'bg-yellow-100 text-yellow-800',
                                                'cancelled'    => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'fully_paid'   => 'Pagado',
                                                'advance_paid' => 'Adelanto',
                                                'pending'      => 'Pendiente',
                                                'cancelled'    => 'Cancelado',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full font-bold uppercase {{ $statusColors[$reserva->status] ?? 'bg-gray-100' }}">
                                            {{ $statusLabels[$reserva->status] ?? $reserva->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No hay registros de reservas en el historial.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-6">
                    {{ $allReservas->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Chart 1: Estados
            const ctxStatus = document.getElementById('chartStatus');
            if(ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pagado', 'Adelanto', 'Pendiente', 'Cancelado'],
                        datasets: [{ 
                            data: [{{ $reservasStatus['fully_paid'] }}, {{ $reservasStatus['advance_paid'] }}, {{ $reservasStatus['pending'] }}, {{ $reservasStatus['cancelled'] }}], 
                            backgroundColor: ['#22C55E', '#3B82F6', '#EAB308', '#EF4444'],
                            hoverOffset: 10,
                            borderWidth: 0
                        }]
                    }, 
                    options: { responsive: true, maintainAspectRatio: false, cutout: '60%' }
                });
            }

            // Chart 2: Horarios
            const ctxHourly = document.getElementById('chartHourly');
            if(ctxHourly) {
                new Chart(ctxHourly, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($hourlyLabels) !!},
                        datasets: [{ 
                            label: 'Reservas Totales', 
                            data: {!! json_encode(array_values($hourlyData)) !!}, 
                            backgroundColor: '#6366F1',
                            borderRadius: 3
                        }]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>