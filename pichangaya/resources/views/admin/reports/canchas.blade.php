<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte Detallado: Canchas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- GRÁFICO GEOGRÁFICO --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-xl mr-2">📍</span> Distribución Geográfica
                </h3>
                <div class="h-80 w-full">
                    <canvas id="chartCanchas"></canvas>
                </div>
            </div>

            {{-- TABLA DETALLADA --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Inventario y Rendimiento</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-yellow-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-yellow-800 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-yellow-800 uppercase">Distrito</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-yellow-800 uppercase">Dueño</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-yellow-800 uppercase">Reservas</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-yellow-800 uppercase">Total Generado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($detailedTopCanchas as $cancha)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cancha->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cancha->district->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cancha->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">{{ $cancha->reservas_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-green-600">
                                        S/ {{ number_format($cancha->reservas_sum_total_price, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay canchas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('chartCanchas');
            if(ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($canchasByDistrict)) !!},
                        datasets: [{ 
                            label: 'Canchas por Distrito', 
                            data: {!! json_encode(array_values($canchasByDistrict)) !!}, 
                            backgroundColor: '#F59E0B',
                            borderRadius: 4
                        }]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        scales: { 
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        } 
                    }
                });
            }
        });
    </script>
</x-app-layout>