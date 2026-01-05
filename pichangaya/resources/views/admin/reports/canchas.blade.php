<x-app-layout>
    {{-- resources/views/admin/reports/canchas.blade.php --}}
    
    {{-- Librería Gráfica --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-400 transition rounded-full hover:bg-gray-100 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Reporte Detallado: Canchas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
<<<<<<< HEAD
            {{-- GRÁFICO GEOGRÁFICO --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-xl mr-2"></span> Distribución Geográfica
                </h3>
                <div class="h-80 w-full">
                    <canvas id="chartCanchas"></canvas>
=======
            {{-- GRID DE GRÁFICOS (2 Columnas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- GRÁFICO 1: GEOGRÁFICO --}}
                <div class="p-6 bg-white border border-gray-100 shadow-lg sm:rounded-2xl">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-indigo-100 rounded-lg text-indigo-600">
                            {{-- Icono Map Pin --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        Distribución Geográfica
                    </h3>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="chartCanchas"></canvas>
                    </div>
>>>>>>> e1292eb26a868daadef5581f52dd506c19d198df
                </div>

<<<<<<< HEAD
            {{-- NUEVO: GRÁFICO DE CANCHAS FAVORITAS --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-xl mr-2"></span> Canchas con más Favoritos (Top 5)
                </h3>
                <div class="h-80 w-full">
                    <canvas id="chartFavoritos"></canvas>
=======
                {{-- GRÁFICO 2: FAVORITOS --}}
                <div class="p-6 bg-white border border-gray-100 shadow-lg sm:rounded-2xl">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-red-100 rounded-lg text-red-600">
                            {{-- Icono Heart --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                        </div>
                        Top 5 Favoritos
                    </h3>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="chartFavoritos"></canvas>
                    </div>
>>>>>>> e1292eb26a868daadef5581f52dd506c19d198df
                </div>
            </div>

            {{-- TABLA DETALLADA --}}
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9 0v-7.5c0-.621.504-1.125 1.125-1.125h3m10.875 0-4.28-4.146a.562.562 0 0 0-.849.69h.662a2.062 2.062 0 0 1 2.062 2.062v4.875c0 .621.504 1.125 1.125 1.125h3.375m-9.375 0h9.375m-9.375 0v-9.375c0-.621.504-1.125 1.125-1.125h.938c.621 0 1.125.504 1.125 1.125v9.375" />
                    </svg>
                    <h3 class="font-bold text-gray-700">Inventario y Rendimiento (Click para gestionar)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Distrito</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Dueño</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Reservas</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-emerald-600 uppercase">Total Generado</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($detailedTopCanchas as $cancha)
                                {{-- LÓGICA DE CLIC: Redirige a la gestión de reservas de la cancha --}}
                                <tr class="hover:bg-indigo-50 transition cursor-pointer group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $cancha) }}'">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-700">{{ $cancha->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cancha->district->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cancha->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">{{ $cancha->reservas_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-emerald-600">
                                        S/ {{ number_format($cancha->reservas_sum_total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto group-hover:text-indigo-600 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No hay canchas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- SCRIPT BLINDADO (Igual al de Ingresos) --}}
    <script>
        (function() {
            function initCharts() {
                // 1. Datos Gráfico Geográfico
                const districtLabels = @json(array_keys($canchasByDistrict));
                const districtData = @json(array_values($canchasByDistrict));

                // 2. Datos Gráfico Favoritos
                const favLabels = @json($favLabels);
                const favData = @json($favData);

                // --- RENDERIZAR GRÁFICO 1 (DISTRITOS) ---
                const ctx1 = document.getElementById('chartCanchas');
                if (ctx1 && typeof Chart !== 'undefined') {
                    // Limpiar anterior si existe
                    if (window.chartGeo) window.chartGeo.destroy();

                    window.chartGeo = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: districtLabels,
                            datasets: [{ 
                                label: 'Canchas', 
                                data: districtData, 
                                backgroundColor: 'rgba(99, 102, 241, 0.6)', // Indigo
                                borderColor: '#4F46E5',
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { 
                                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                                x: { grid: { display: false } }
                            } 
                        }
                    });
                }

                // --- RENDERIZAR GRÁFICO 2 (FAVORITOS) ---
                const ctx2 = document.getElementById('chartFavoritos');
                if (ctx2 && typeof Chart !== 'undefined') {
                    // Limpiar anterior si existe
                    if (window.chartFav) window.chartFav.destroy();

                    window.chartFav = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: favLabels,
                            datasets: [{ 
                                label: 'Favoritos', 
                                data: favData, 
                                backgroundColor: 'rgba(239, 68, 68, 0.7)', // Rojo
                                borderColor: '#EF4444',
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        }, 
                        options: { 
                            indexAxis: 'y', // Barra Horizontal
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { 
                                x: { beginAtZero: true, ticks: { stepSize: 1 } },
                                y: { grid: { display: false } }
                            } 
                        }
                    });
                }
            }

            // Ejecutar al cargar todo
            window.addEventListener('load', initCharts);
            // Fallback por si ya cargó
            if (document.readyState === 'complete') initCharts();
        })();
    </script>
</x-app-layout>