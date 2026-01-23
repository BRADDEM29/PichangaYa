<x-app-layout>
    {{-- resources/views/admin/reports/canchas.blade.php --}}
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <header class="flex items-center gap-4 py-1">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-orange-50 hover:border-orange-200 hover:text-orange-600 hover:shadow-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Reporte <span class="text-orange-600">Canchas</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Panel de Administración • Análisis Geográfico y Popularidad
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                
                <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-orange-100 rounded-lg text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        Distribución por Distrito
                    </h3>
                    <div class="h-72">
                        <canvas id="chartCanchas"></canvas>
                    </div>
                </section>

                <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-orange-100 rounded-lg text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                        </div>
                        Top 5 Más Favoritas
                    </h3>
                    <div class="h-72">
                        <canvas id="chartFavoritos"></canvas>
                    </div>
                </section>
            </div>

            <article class="overflow-hidden bg-white border border-gray-100 shadow-xl sm:rounded-3xl">
                <header class="flex flex-col justify-between gap-4 px-6 py-5 border-b border-gray-100 md:flex-row md:items-center bg-gray-50">
                    <h3 class="flex items-center font-bold text-gray-800 uppercase text-xs tracking-widest">
                        <div class="p-2 mr-3 bg-orange-100 rounded-lg text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 15.75h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                        </div>
                        Rendimiento Individual de Canchas
                    </h3>
                </header>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Cancha</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Ubicación</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Administrador</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-right text-gray-500 uppercase tracking-widest">Reservas</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-right text-orange-600 uppercase tracking-widest">Total Ingresos</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-center text-gray-400 uppercase tracking-widest">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($detailedTopCanchas as $cancha)
                                <tr class="transition cursor-pointer hover:bg-orange-50/50 group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $cancha) }}'">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-orange-700 transition">{{ $cancha->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-[10px] font-bold bg-gray-100 text-gray-600 rounded-lg uppercase">
                                            {{ $cancha->district->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ $cancha->user->name ?? 'Sin asignar' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-gray-800">
                                        {{ $cancha->reservas_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-orange-600">
                                        S/ {{ number_format($cancha->reservas_sum_total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto transition transform group-hover:text-orange-600 group-hover:translate-x-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 uppercase text-xs font-bold tracking-widest">
                                        No se encontraron datos de canchas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </div>
    </main>

    <script>
        (function() {
            function initCharts() {
                const districtLabels = @json(array_keys($canchasByDistrict));
                const districtData = @json(array_values($canchasByDistrict));
                const favLabels = @json($favLabels);
                const favData = @json($favData);

                const ctx1 = document.getElementById('chartCanchas');
                if (ctx1 && typeof Chart !== 'undefined') {
                    if (window.chartGeo) window.chartGeo.destroy();
                    window.chartGeo = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: districtLabels,
                            datasets: [{ 
                                data: districtData, 
                                backgroundColor: '#EA580C',
                                borderRadius: 6
                            }]
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { 
                                y: { beginAtZero: true, grid: { borderDash: [5,5], color: '#f3f4f6' }, ticks: { stepSize: 1, font: { weight: 'bold' } } },
                                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                            }
                        }
                    });
                }

                const ctx2 = document.getElementById('chartFavoritos');
                if (ctx2 && typeof Chart !== 'undefined') {
                    if (window.chartFav) window.chartFav.destroy();
                    window.chartFav = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: favLabels,
                            datasets: [{ 
                                data: favData, 
                                backgroundColor: '#F97316',
                                borderRadius: 6
                            }]
                        }, 
                        options: { 
                            indexAxis: 'y',
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { 
                                x: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1, font: { weight: 'bold' } } },
                                y: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                            }
                        }
                    });
                }
            }

            window.addEventListener('load', initCharts);
            if (document.readyState === 'complete') initCharts();
        })();
    </script>
</x-app-layout>