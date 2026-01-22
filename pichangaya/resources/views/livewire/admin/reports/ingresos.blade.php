{{-- resources/views/livewire/admin/reports/ingresos.blade.php --}}
<section class="space-y-8">
    {{-- Scripts integrados --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    {{-- SECCIÓN SUPERIOR: TARJETAS Y GRÁFICO --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        {{-- CARD: TOTAL INGRESOS --}}
        <article class="lg:col-span-1 flex flex-col justify-center p-6 border border-emerald-200 bg-emerald-50 rounded-2xl relative overflow-hidden shadow-sm h-full min-h-[150px]">
            <header class="z-10 relative">
                <h2 class="text-[10px] font-black tracking-widest text-emerald-600 uppercase mb-2">
                    Ingreso Total ({{ $range == 'day' ? 'Hoy' : ($range == 'week' ? 'Semana' : ($range == 'month' ? 'Mes' : 'Año')) }})
                </h2>
                <data value="{{ $ingresosDetallados->sum('total_price') }}" class="text-4xl md:text-5xl font-black text-gray-800">
                    S/ {{ number_format($ingresosDetallados->sum('total_price'), 2) }}
                </data>
                <footer class="text-sm text-emerald-600/80 mt-2 font-medium">
                    {{ $ingresosDetallados->count() }} transacciones registradas
                </footer>
            </header>
            
            <figure class="absolute top-6 right-6 p-4 text-emerald-600 bg-white rounded-2xl shadow-sm z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </figure>
            <aside class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-100 rounded-full opacity-50"></aside>
        </article>

        {{-- CARD: GRÁFICO TENDENCIA --}}
        <section class="lg:col-span-2 p-6 bg-white shadow-sm rounded-2xl border border-gray-100 flex flex-col"
                 x-data="{
                    chart: null,
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
                                    tension: 0.3, 
                                    fill: true 
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                        });
                    }
                 }"
                 x-init="renderChart(@js($chartData)); Livewire.on('update-ingresos-chart', e => renderChart(e.data || e[0]?.data || e))">
            
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Tendencia de Ingresos
                </h3>
                <nav class="flex p-1 bg-gray-100 rounded-xl">
                    @foreach(['day' => 'Día', 'week' => 'Sem', 'month' => 'Mes', 'year' => 'Año'] as $key => $label)
                        <button wire:click="setRange('{{ $key }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === $key ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </header>

            <figure class="h-72 w-full relative">
                <canvas id="ingresosChartCanvas"></canvas>
            </figure>
        </section>
    </section>

    {{-- TABLA DETALLADA --}}
    <section class="overflow-hidden bg-white shadow-sm border border-gray-100 rounded-2xl">
        <header class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
            <h3 class="font-black text-xs uppercase tracking-widest text-gray-500 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Detalle de Transacciones
            </h3>
        </header>
        
        <section class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-6 py-4">Fecha Pago</th>
                        <th class="px-6 py-4">Fecha Juego</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Cancha</th>
                        <th class="px-6 py-4 text-right">Monto</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($ingresosDetallados as $reserva)
                        <tr class="hover:bg-emerald-50/30 transition-colors group cursor-pointer" onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                            <td class="px-6 py-4">
                                <hgroup class="flex flex-col">
                                    <span class="text-xs font-black text-gray-700">{{ $reserva->updated_at->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $reserva->updated_at->format('H:i') }}</span>
                                </hgroup>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-600">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y - h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-800">{{ $reserva->user->name ?? 'Invitado' }}</td>
                            <td class="px-6 py-4">
                                <hgroup class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700">{{ $reserva->cancha->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase">{{ $reserva->cancha->district->name ?? '' }}</span>
                                </hgroup>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <data value="{{ $reserva->total_price }}" class="px-3 py-1 text-xs font-black text-emerald-700 bg-emerald-100 rounded-lg">
                                    + S/ {{ number_format($reserva->total_price, 2) }}
                                </data>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No hay ingresos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </section>
</section>