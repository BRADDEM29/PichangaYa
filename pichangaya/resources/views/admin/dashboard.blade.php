<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\dashboard.blade.php --}}
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control General') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- CARD VERDE --}}
                <a href="{{ route('admin.reports.ingresos') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-8 border-green-500 flex items-center justify-between group-hover:bg-green-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Pagos Completos</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($ingresosTotales, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1 font-bold group-hover:text-green-700">Ver detalles <span class="text-lg">→</span></p>
                        </div>
                        <div class="p-4 rounded-full bg-green-100 text-green-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>
                
                {{-- CARD AZUL --}}
                <a href="{{ route('admin.reports.adelantados') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-8 border-blue-500 flex items-center justify-between group-hover:bg-blue-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Pagos Adelantados</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($adelantosTotal, 2) }}</p>
                            <p class="text-xs text-blue-600 mt-2 flex items-center gap-1 font-bold group-hover:text-blue-800">
                                {{ $adelantosCount }} Reservas <span class="text-lg">→</span>
                            </p>
                        </div>
                        <div class="p-4 rounded-full bg-blue-100 text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                </a>

                {{-- CARD AMARILLA --}}
                <a href="{{ route('admin.reports.pendientes') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-8 border-yellow-400 flex items-center justify-between group-hover:bg-yellow-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-1">Por Cobrar</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($pendientesMoney, 2) }}</p>
                            <p class="text-xs text-yellow-600 font-bold mt-2">{{ $pendientesCount }} Reservas en espera</p>
                        </div>
                        <div class="p-4 rounded-full bg-yellow-100 text-yellow-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>

                {{-- CARD ROJA --}}
                <a href="{{ route('admin.reports.cancelados') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border-l-8 border-red-500 flex items-center justify-between group-hover:bg-red-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Ingreso Perdido</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($canceladosMoney, 2) }}</p>
                            <p class="text-xs text-red-600 font-bold mt-2">{{ $canceladosCount }} Reservas caídas</p>
                        </div>
                        <div class="p-4 rounded-full bg-red-100 text-red-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- 2. OPERATIVO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.reports.reservas') }}" class="block group bg-white shadow p-4 rounded-lg flex items-center border hover:border-indigo-300 transition hover:-translate-y-0.5">
                    <div class="p-3 bg-indigo-100 rounded text-indigo-600 mr-4"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Total Histórico</p><p class="text-xl font-bold text-gray-800">{{ $reservasTotales }} Reservas</p></div>
                </a>
                <a href="{{ route('admin.reports.usuarios') }}" class="block group bg-white shadow p-4 rounded-lg flex items-center border hover:border-blue-300 transition hover:-translate-y-0.5">
                    <div class="p-3 bg-blue-100 rounded text-blue-600 mr-4"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Base de Usuarios</p><p class="text-xl font-bold text-gray-800">{{ $totalUsers }} Registrados</p></div>
                </a>
                <a href="{{ route('admin.reports.canchas') }}" class="block group bg-white shadow p-4 rounded-lg flex items-center border hover:border-yellow-300 transition hover:-translate-y-0.5">
                    <div class="p-3 bg-yellow-100 rounded text-yellow-600 mr-4"><span class="text-2xl">🏟️</span></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Inventario</p><p class="text-xl font-bold text-gray-800">{{ $totalCanchas }} Canchas</p></div>
                </a>
            </div>

            {{-- ========================================== --}}
            {{-- 3. GRÁFICO BURSÁTIL (MULTI-LÍNEA) --}}
            {{-- ========================================== --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Tendencia Financiera (Últimos 15 días)
                    </h3>
                    {{-- Leyenda personalizada --}}
                    <div class="flex gap-4 text-xs font-bold">
                        <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span> Pagado</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-1"></span> Adelanto</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-yellow-400 rounded-full mr-1"></span> Pendiente</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> Perdido</span>
                    </div>
                </div>
                
                <div class="h-96 w-full relative">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- 4. COMPOSICIÓN DE USUARIOS --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Composición de Usuarios</h3>
                    <div class="h-48 w-full flex justify-center mb-6 relative">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Clientes</span> <span>{{ $usersByRole['users'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($usersByRole['users'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Dueños</span> <span>{{ $usersByRole['owners'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($usersByRole['owners'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Admins</span> <span>{{ $usersByRole['admins'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($usersByRole['admins'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. CANCHAS POPULARES --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2 text-xl">🏆</span> Canchas Más Populares
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Distrito</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Reservas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($topCanchas as $cancha)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cancha->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $cancha->district->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-black text-indigo-600">{{ $cancha->reservas_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 6. ÚLTIMAS RESERVAS --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Últimas Reservas (Incluye Usuarios Eliminados)
                    </h3>
                    <a href="{{ route('admin.reports.reservas') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold hover:underline">Ver todas</a>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($recentReservas as $reserva)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->user)
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900">{{ $reserva->user->name }}
                                                    @if($reserva->user->trashed()) <span class="text-red-500 text-[10px] ml-1">(Eliminado)</span> @endif
                                                </span>
                                                <span class="text-xs text-gray-400">{{ $reserva->user->email }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-red-500 font-bold italic">Usuario purgado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reserva->cancha->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">S/ {{ number_format($reserva->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->status == 'fully_paid')
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800">Pagado</span>
                                        @elseif($reserva->status == 'advance_paid')
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800">Adelanto</span>
                                        @elseif($reserva->status == 'pending')
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-800">Cancelado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay actividad reciente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (GRÁFICOS) --}}
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 🟢 1. GRÁFICO MULTI-SERIE (BOLSA DE VALORES)
            const ctxFin = document.getElementById('financialChart');
            if (ctxFin) {
                new Chart(ctxFin.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {{ Js::from($fechas ?? []) }},
                        datasets: [
                            {
                                label: 'Pagado',
                                data: {{ Js::from($dataFullyPaid ?? []) }},
                                borderColor: '#10B981', // Verde
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                tension: 0.4
                            },
                            {
                                label: 'Adelanto',
                                data: {{ Js::from($dataAdvance ?? []) }},
                                borderColor: '#3B82F6', // Azul
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 3,
                                tension: 0.4
                            },
                            {
                                label: 'Pendiente',
                                data: {{ Js::from($dataPending ?? []) }},
                                borderColor: '#FBBF24', // Amarillo
                                borderDash: [5, 5],
                                borderWidth: 2,
                                tension: 0.4
                            },
                            {
                                label: 'Perdido',
                                data: {{ Js::from($dataCancelled ?? []) }},
                                borderColor: '#EF4444', // Rojo
                                borderWidth: 2,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { display: false } }, // Leyenda custom
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 🟢 2. GRÁFICO DE USUARIOS
            const ctxUser = document.getElementById('userRoleChart');
            if (ctxUser) {
                new Chart(ctxUser.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Clientes', 'Dueños', 'Admins'],
                        datasets: [{
                            data: [{{ $usersByRole['users'] }}, {{ $usersByRole['owners'] }}, {{ $usersByRole['admins'] }}],
                            backgroundColor: ['#3B82F6', '#22C55E', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>