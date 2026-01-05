<div class="space-y-8">
    
    {{-- HEADER CON FILTROS --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-xl font-semibold leading-tight text-emerald-800 flex items-center gap-2">
            <span class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
            Historial de Ingresos
        </h2>

        {{-- BOTONES DE FILTRO (LIVEWIRE) --}}
        <div class="bg-gray-100 p-1 rounded-xl flex overflow-x-auto">
            @foreach(['day' => 'Hoy', 'week' => '7 Días', 'month' => 'Mes', 'year' => 'Este Año'] as $key => $label)
                <button 
                    wire:click="setRange('{{ $key }}')"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $range === $key ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- SECCIÓN 1: GRÁFICO DINÁMICO --}}
    <div 
        class="w-full p-6 bg-white border border-gray-100 shadow-lg sm:rounded-2xl"
        x-data="{
            chart: null,
            init() {
                // Cargar datos iniciales
                this.renderChart(@js($chartData));

                // Escuchar evento de Livewire (cuando cambias el filtro)
                Livewire.on('update-ingresos-chart', (event) => {
                    const data = event.data || event[0]?.data || event;
                    this.renderChart(data);
                });
            },
            renderChart(data) {
                const ctx = document.getElementById('chartIngresos');
                if (!ctx) return;

                if (this.chart) this.chart.destroy();

                this.chart = new Chart(ctx, {
                    type: 'line', // Lineal se ve mejor para tendencias de tiempo
                    data: {
                        labels: data.labels,
                        datasets: [{ 
                            label: 'Ingresos (S/)', 
                            data: data.values, 
                            backgroundColor: (context) => {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
                                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
                                return gradient;
                            },
                            borderColor: '#10B981',
                            borderWidth: 3,
                            pointRadius: 3,
                            fill: true,
                            tension: 0.3
                        }]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { callback: (v) => 'S/ ' + v } },
                            x: { grid: { display: false } } 
                        } 
                    }
                });
            }
        }"
    >
        <div class="relative w-full h-[350px]">
            <canvas id="chartIngresos"></canvas>
        </div>
    </div>

    {{-- SECCIÓN 2: TABLA DETALLADA (Se actualiza sola con Livewire) --}}
    <div class="bg-white shadow-lg sm:rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-emerald-50 border-b border-gray-100">
            <h3 class="font-bold text-emerald-800 text-sm">Detalle de Transacciones ({{ ucfirst($range) }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">Cancha</th>
                        <th class="px-6 py-3 text-xs font-bold text-right text-emerald-600 uppercase">Monto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ingresosDetallados as $reserva)
                        <tr class="hover:bg-emerald-50 transition cursor-pointer"
                            onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha_id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $reserva->cancha->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="px-3 py-1 inline-flex text-sm font-black text-emerald-700 bg-emerald-100 rounded-full">
                                    S/ {{ number_format($reserva->total_price, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">No hay ingresos en este periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>