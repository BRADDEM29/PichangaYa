<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\reservas.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <header class="flex items-center gap-4 py-1">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 hover:shadow-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Reporte <span class="text-indigo-600">Reservas</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Panel de Administración • Gestión de Horarios
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">
            
            {{-- SECCIÓN DE GRÁFICOS --}}
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                    <h3 class="flex items-center mb-4 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                        </div>
                        Distribución por Estados
                    </h3>
                    <div class="h-64">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </section>

                <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                    <h3 class="flex items-center mb-4 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-blue-100 rounded-lg text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        Demanda por Horas
                    </h3>
                    <div class="h-64">
                        <canvas id="chartHourly"></canvas>
                    </div>
                </section>
            </div>

            {{-- TABLA DE HISTORIAL --}}
            <article class="overflow-hidden bg-white border border-gray-100 shadow-xl sm:rounded-3xl">
                <header class="flex flex-col justify-between gap-4 px-6 py-5 border-b border-gray-100 md:flex-row md:items-center bg-gray-50">
                    <h3 class="flex items-center font-bold text-gray-800 uppercase text-xs tracking-widest">
                        <div class="p-2 mr-3 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-3.75 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        Historial Completo de Reservas (Usuarios Eliminados Incluidos)
                    </h3>
                </header>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Usuario</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Cancha</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-left text-gray-500 uppercase tracking-widest">Fecha y Hora</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-right text-gray-500 uppercase tracking-widest">Precio</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-center text-gray-500 uppercase tracking-widest">Estado</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-center text-gray-400 uppercase tracking-widest">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($allReservas as $reserva)
                                <tr class="transition cursor-pointer hover:bg-indigo-50/50 group"
                                    role="link"
                                    @if($reserva->cancha)
                                        onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'"
                                    @endif>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-gray-900 group-hover:text-indigo-700 transition">{{ $reserva->user->name ?? 'N/A' }}</p>
                                                @if($reserva->user && $reserva->user->trashed())
                                                    <span class="px-1.5 py-0.5 text-[9px] font-black bg-red-50 text-red-600 border border-red-100 rounded uppercase">Eliminado</span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-400 font-medium">{{ $reserva->user->email ?? '' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 font-bold">
                                        {{ $reserva->cancha->name ?? 'Cancha eliminada' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase">
                                            {{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-right text-gray-900">
                                        S/ {{ number_format($reserva->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusColors = [
                                                'fully_paid'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'advance_paid' => 'bg-sky-100 text-sky-800 border-sky-200',
                                                'pending'      => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'cancelled'    => 'bg-rose-100 text-rose-800 border-rose-200',
                                            ];
                                            $statusLabels = [
                                                'fully_paid'   => 'Pagado',
                                                'advance_paid' => 'Adelanto',
                                                'pending'      => 'Pendiente',
                                                'cancelled'    => 'Cancelado',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border {{ $statusColors[$reserva->status] ?? 'bg-gray-100 border-gray-200' }}">
                                            {{ $statusLabels[$reserva->status] ?? $reserva->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto transition transform group-hover:text-indigo-600 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 uppercase text-xs font-bold tracking-widest">
                                        No hay registros de reservas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <footer class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                    {{ $allReservas->links() }}
                </footer>
            </article>
        </div>
    </main>

    {{-- LÓGICA DE GRÁFICOS ORIGINAL --}}
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
                            backgroundColor: ['#10B981', '#0EA5E9', '#F59E0B', '#EF4444'],
                            hoverOffset: 10,
                            borderWidth: 0
                        }]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, font: { weight: 'bold', size: 11 } }
                            }
                        }
                    }
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
                            borderRadius: 8
                        }]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                grid: { borderDash: [5, 5], color: '#f3f4f6' }, 
                                ticks: { stepSize: 1, font: { weight: 'bold' } } 
                            },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>