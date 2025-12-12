<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reporte Detallado: Usuarios') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- GRÁFICO CRECIMIENTO --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Nuevos Usuarios (Últimos 6 Meses)
                </h3>
                <div class="h-80 w-full">
                    <canvas id="chartGrowth"></canvas>
                </div>
            </div>

            {{-- ROLES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-xl sm:rounded-lg p-6 md:col-span-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Distribución de Roles</h3>
                    <div class="h-60 w-full">
                        <canvas id="chartRoles"></canvas>
                    </div>
                </div>
                
                <div class="bg-white shadow-xl sm:rounded-lg p-6 md:col-span-2 flex flex-col justify-center">
                    <h4 class="text-gray-500 uppercase font-bold text-xs mb-4">Resumen Actual</h4>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-3xl font-black text-blue-600">{{ $usersByRole['users'] }}</p>
                            <p class="text-sm text-blue-800 font-bold">Clientes</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-3xl font-black text-green-600">{{ $usersByRole['owners'] }}</p>
                            <p class="text-sm text-green-800 font-bold">Dueños</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                            <p class="text-3xl font-black text-red-600">{{ $usersByRole['admins'] }}</p>
                            <p class="text-sm text-red-800 font-bold">Admins</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Growth Chart
            const ctxGrowth = document.getElementById('chartGrowth');
            if(ctxGrowth) {
                new Chart(ctxGrowth, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($growthLabels) !!},
                        datasets: [{ 
                            label: 'Nuevos Registros', 
                            data: {!! json_encode($growthData) !!}, 
                            borderColor: '#3B82F6', 
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5
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

            // Roles Chart
            const ctxRoles = document.getElementById('chartRoles');
            if(ctxRoles) {
                new Chart(ctxRoles, {
                    type: 'pie',
                    data: {
                        labels: ['Clientes', 'Dueños', 'Admins'],
                        datasets: [{
                            data: [{{ $usersByRole['users'] }}, {{ $usersByRole['owners'] }}, {{ $usersByRole['admins'] }}],
                            backgroundColor: ['#3B82F6', '#22C55E', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    </script>
</x-app-layout>