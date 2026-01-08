{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\admin\financial-chart.blade.php --}}
<div 
    class="w-full p-6 bg-white border border-gray-100 shadow-lg dark:bg-gray-800 rounded-2xl dark:border-gray-700"
    x-data="{
        chart: null,
        
        // Inicialización automática de Alpine
        init() {
            // Cargar Chart.js si no existe
            if (typeof Chart === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.onload = () => {
                    this.renderChart(@js($chartData));
                };
                document.head.appendChild(script);
            } else {
                // Esperamos un momento a que el DOM esté listo
                this.$nextTick(() => {
                    this.renderChart(@js($chartData));
                });
            }

            // Escuchar cambios desde Livewire (cuando haces clic en los botones)
            Livewire.on('update-chart', (event) => {
                // Soporte para diferentes versiones de Livewire
                const data = event.data || event[0]?.data || event;
                this.renderChart(data);
            });
        },

        // Función para dibujar/actualizar el gráfico
        renderChart(data) {
            const ctx = document.getElementById('financialChartCanvas');
            
            if (!ctx) return;

            // Si ya existe un gráfico, lo destruimos para crear el nuevo limpio
            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        { 
                            label: 'Pagado', 
                            data: data.datasets.paid, 
                            borderColor: '#10B981', 
                            backgroundColor: (context) => {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
                                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
                                return gradient;
                            },
                            borderWidth: 3, pointRadius: 0, pointHoverRadius: 6, tension: 0.4, fill: true 
                        },
                        { label: 'Adelanto', data: data.datasets.advance, borderColor: '#3B82F6', borderWidth: 2, pointRadius: 0, tension: 0.4, fill: false },
                        { label: 'Pendiente', data: data.datasets.pending, borderColor: '#F59E0B', borderWidth: 2, borderDash: [4, 4], pointRadius: 0, tension: 0.4, fill: false },
                        { label: 'Perdido', data: data.datasets.cancelled, borderColor: '#EF4444', borderWidth: 2, pointRadius: 0, tension: 0.4, fill: false }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { borderDash: [4, 4], color: '#e5e7eb' }, 
                            ticks: { font: { size: 10 }, color: '#9CA3AF', callback: (val) => 'S/ ' + val }, 
                            border: { display: false } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { font: { size: 10 }, color: '#9CA3AF', maxTicksLimit: 8 }, 
                            border: { display: false } 
                        }
                    }
                }
            });
        }
    }"
>
    
    {{-- HEADER: Título y Botones --}}
    <div class="flex flex-col justify-between gap-4 mb-6 md:flex-row md:items-center">
        <div>
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                <span class="p-2 text-indigo-600 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 19l16 0"></path><path d="M4 15l4 -6l4 2l4 -5l4 4"></path>
                    </svg>
                </span>
                Tendencia Financiera
                {{-- Spinner de carga --}}
                <div wire:loading class="ml-2">
                    <svg class="w-4 h-4 text-indigo-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </h3>
            <p class="mt-1 text-sm text-gray-500 ml-11 dark:text-gray-400">Ingresos y reservas en el tiempo</p>
        </div>

        {{-- BOTONES: Usamos wire:click para NO recargar --}}
        <div class="flex self-start p-1 overflow-x-auto bg-gray-100 rounded-xl dark:bg-gray-700/50 md:self-auto">
            @foreach(['day' => 'Día', 'week' => 'Semana', '15d' => '15 Días', 'month' => 'Mes', 'year' => 'Año'] as $key => $label)
                <button 
                    wire:click="setRange('{{ $key }}')"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $range === $key ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- LEYENDA --}}
    <div class="flex flex-wrap items-center gap-4 mb-6 ml-2 text-xs font-medium text-gray-600 dark:text-gray-300">
        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Pagado</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Adelanto</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Pendiente</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Perdido</div>
    </div>

    {{-- GRÁFICO: Canvas protegido con wire:ignore --}}
    <div class="relative w-full h-[350px]" wire:ignore>
        <canvas id="financialChartCanvas"></canvas>
    </div>
</div>