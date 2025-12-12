<x-app-layout>
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- GRÁFICO 1 --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Estado de las Reservas</h3>
                    <div class="h-72 w-full">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>

                {{-- GRÁFICO 2 --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Horas de Mayor Demanda</h3>
                    <div class="h-72 w-full">
                        <canvas id="chartHourly"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-sm text-indigo-800 flex items-center gap-2">
                <span class="text-xl">💡</span>
                <p>El gráfico de "Horas de Mayor Demanda" muestra el acumulado histórico de reservas según la hora de inicio. Útil para identificar horarios Prime.</p>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Chart 1
            const ctxStatus = document.getElementById('chartStatus');
            if(ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Confirmadas', 'Pendientes', 'Canceladas'],
                        datasets: [{ 
                            data: [{{ $reservasStatus['confirmed'] }}, {{ $reservasStatus['pending'] }}, {{ $reservasStatus['cancelled'] }}], 
                            backgroundColor: ['#22C55E', '#EAB308', '#EF4444'],
                            hoverOffset: 10,
                            borderWidth: 0
                        }]
                    }, 
                    options: { responsive: true, maintainAspectRatio: false, cutout: '60%' }
                });
            }

            // Chart 2
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
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>