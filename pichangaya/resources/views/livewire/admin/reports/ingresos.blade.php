<div>
    {{-- Cargar Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-500 transition rounded-full hover:bg-gray-200" title="Volver al Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h2 class="text-xl font-semibold leading-tight text-emerald-700">Reporte de Ingresos</h2>
    </div>

    <div class="space-y-8">

        {{-- SECCIÓN SUPERIOR: TARJETAS Y GRÁFICO --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            {{-- CARD 1: TOTAL INGRESOS (Ocupa 1 columna en pantallas grandes) --}}
            <div class="lg:col-span-1 flex flex-col justify-center p-6 border border-emerald-200 bg-emerald-50 rounded-xl relative overflow-hidden shadow-sm h-full min-h-[150px]">
                <div class="z-10 relative">
                    <p class="text-xs font-black tracking-widest text-emerald-600 uppercase mb-2">
                        Ingreso Total ({{ $range == 'day' ? 'Hoy' : ($range == 'week' ? 'Semana' : ($range == 'month' ? 'Mes' : 'Año')) }})
                    </p>
                    <p class="text-4xl md:text-5xl font-black text-gray-800">
                        S/ {{ number_format($ingresosDetallados->sum('total_price'), 2) }}
                    </p>
                    <p class="text-sm text-emerald-600/80 mt-2 font-medium">
                        {{ $ingresosDetallados->count() }} transacciones registradas
                    </p>
                </div>
                {{-- Icono flotante --}}
                <div class="absolute top-6 right-6 p-4 text-emerald-600 bg-white rounded-full shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                {{-- Decoración de fondo --}}
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-100 rounded-full opacity-50"></div>
            </div>

            {{-- CARD 2: GRÁFICO LINEAL (Ocupa 2 columnas para ser MAS GRANDE) --}}
            <div class="lg:col-span-2 p-5 bg-white shadow rounded-xl border border-gray-100 flex flex-col"
                 x-data="{
                    chart: null,
                    init() {
                        if (typeof Chart === 'undefined') {
                            const script = document.createElement('script');
                            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                            script.onload = () => { this.renderChart(@js($chartData)); };
                            document.head.appendChild(script);
                        } else {
                            this.$nextTick(() => { this.renderChart(@js($chartData)); });
                        }

                        Livewire.on('update-ingresos-chart', (event) => {
                            const data = event.data || event[0]?.data || event;
                            this.renderChart(data);
                        });
                    },
                    renderChart(data) {
                        const ctx = document.getElementById('ingresosChartCanvas');
                        if (!ctx) return;
                        if (this.chart) { this.chart.destroy(); }

                        this.chart = new Chart(ctx.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{ 
                                    label: 'Ingresos', 
                                    data: data.values,
                                    borderColor: '#10B981', 
                                    backgroundColor: (context) => {
                                        const ctx = context.chart.ctx;
                                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
                                        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
                                        return gradient;
                                    },
                                    borderWidth: 3, 
                                    pointRadius: 4, 
                                    pointHoverRadius: 6,
                                    tension: 0.3, 
                                    fill: true 
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { 
                                        beginAtZero: true, 
                                        grid: { borderDash: [4, 4] },
                                        ticks: { callback: (val) => 'S/ ' + val } 
                                    },
                                    x: { 
                                        grid: { display: false }, 
                                        ticks: { font: { size: 11 } } 
                                    }
                                }
                            }
                        });
                    }
                 }">
                
                {{-- Header del Gráfico --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Tendencia de Ingresos
                    </h4>
                    
                    {{-- Botones de Rango --}}
                    <div class="flex p-1 bg-gray-100 rounded-lg self-end sm:self-auto">
                        @foreach(['day' => 'Día', 'week' => 'Sem', 'month' => 'Mes', 'year' => 'Año'] as $key => $label)
                            <button 
                                wire:click="setRange('{{ $key }}')"
                                class="px-3 py-1 rounded-md text-xs font-bold transition-all duration-200 
                                {{ $range === $key 
                                    ? 'bg-white text-emerald-700 shadow-sm ring-1 ring-black/5' 
                                    : 'text-gray-400 hover:text-gray-600' 
                                }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- CANVAS AUMENTADO DE TAMAÑO (h-80 = 20rem / 320px) --}}
                <div class="h-80 w-full relative">
                    <canvas id="ingresosChartCanvas"></canvas>
                </div>
            </div>
        </div>

        {{-- TABLA DETALLADA --}}
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl ring-1 ring-gray-900/5">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white flex justify-between items-center">
                <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Detalle de Transacciones
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            {{-- COLUMNA 1: FECHA TRANSACCIÓN (PAGO) --}}
                            <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider bg-gray-50/50">
                                Fecha Pago
                            </th>
                            {{-- COLUMNA 2: FECHA JUEGO (RESERVA) --}}
                            <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">
                                Fecha Juego
                            </th>
                            <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">
                                Cancha
                            </th>
                            <th class="px-6 py-3 text-xs font-bold text-right text-gray-500 uppercase tracking-wider">
                                Monto
                            </th>
                            <th class="px-6 py-3 text-xs font-bold text-center text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($ingresosDetallados as $reserva)
                            <tr class="transition hover:bg-emerald-50 group cursor-pointer"
                                onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                
                                {{-- 1. Fecha Pago (Updated_at o Created_at) --}}
                                <td class="px-6 py-4 whitespace-nowrap bg-gray-50/30">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 text-emerald-600 bg-emerald-100 rounded-full">
                                            {{-- Icono de Billete/Pago --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">
                                                {{ $reserva->updated_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono">
                                                {{ $reserva->updated_at->format('H:i:s') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Fecha Juego (Start_time) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        {{-- Icono Calendario --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">
                                                {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-emerald-600 font-bold">
                                                {{ \Carbon\Carbon::parse($reserva->start_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 3. Cliente --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold border border-gray-200">
                                            {{ substr($reserva->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reserva->user->name ?? 'Usuario Eliminado' }}
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Cancha --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $reserva->cancha->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $reserva->cancha->district->name ?? '' }}</div>
                                </td>

                                {{-- 5. Monto --}}
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-sm font-black text-emerald-700 rounded-full bg-emerald-100 group-hover:bg-white group-hover:shadow-sm transition border border-transparent group-hover:border-emerald-100">
                                        + S/ {{ number_format($reserva->total_price, 2) }}
                                    </span>
                                </td>

                                {{-- 6. Flecha --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto transition-transform duration-200 transform group-hover:text-emerald-500 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-white">
                                    <div class="flex flex-col items-center">
                                        <div class="p-3 bg-gray-50 rounded-full mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="font-medium text-gray-500">No hay ingresos registrados en este rango.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>