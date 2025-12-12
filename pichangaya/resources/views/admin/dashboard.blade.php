<x-app-layout>
    {{-- Carga de Chart.js --}}
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
            
            {{-- ========================================== --}}
            {{-- 1. FILA FINANCIERA (ESTADOS DE DINERO) --}}
            {{-- ========================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- 1. INGRESOS CONFIRMADOS (Verde) --}}
                <a href="{{ route('admin.reports.ingresos') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg p-6 border-l-8 border-green-500 group-hover:bg-green-50 transition-colors h-full flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-widest mb-1">Ingresos Confirmados</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($ingresosTotales, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1 font-bold group-hover:text-green-700">Ver detalles <span class="text-lg">→</span></p>
                        </div>
                        <div class="p-4 rounded-full bg-green-100 text-green-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>

                {{-- 2. PENDIENTES POR COBRAR (Amarillo) --}}
                <a href="{{ route('admin.reports.pendientes') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg p-6 border-l-8 border-yellow-400 group-hover:bg-yellow-50 transition-colors h-full flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-1">Por Cobrar (Pendientes)</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($pendientesMoney, 2) }}</p>
                            <p class="text-xs text-yellow-600 font-bold mt-2">{{ $pendientesCount }} Reservas en espera</p>
                        </div>
                        <div class="p-4 rounded-full bg-yellow-100 text-yellow-600 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>

                {{-- 3. CANCELADOS / PERDIDOS (Rojo) --}}
                <a href="{{ route('admin.reports.cancelados') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg p-6 border-l-8 border-red-500 group-hover:bg-red-50 transition-colors h-full flex items-center justify-between">
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

            {{-- ========================================== --}}
            {{-- 2. FILA DE GESTIÓN (OPERATIVO) --}}
            {{-- ========================================== --}}
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
            {{-- 3. GRÁFICO PRINCIPAL --}}
            {{-- ========================================== --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Ingresos Confirmados (Últimos 30 Días)
                </h3>
                <div class="h-80 w-full">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- ========================================== --}}
                {{-- 4. COMPOSICIÓN DE USUARIOS (BARRAS + PASTEL) --}}
                {{-- ========================================== --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 flex flex-col justify-between">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Composición de Usuarios</h3>
                    
                    {{-- Gráfico Circular --}}
                    <div class="h-48 w-full flex justify-center mb-6">
                        <canvas id="userRoleChart"></canvas>
                    </div>

                    {{-- Barritas de Progreso --}}
                    <div class="space-y-4">
                        {{-- Clientes --}}
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Clientes</span>
                                <span>{{ $usersByRole['users'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full shadow-sm" style="width: {{ $totalUsers > 0 ? ($usersByRole['users'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Dueños --}}
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Dueños de Cancha</span>
                                <span>{{ $usersByRole['owners'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full shadow-sm" style="width: {{ $totalUsers > 0 ? ($usersByRole['owners'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Admins --}}
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase text-gray-500">
                                <span>Administradores</span>
                                <span>{{ $usersByRole['admins'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-red-500 h-2.5 rounded-full shadow-sm" style="width: {{ $totalUsers > 0 ? ($usersByRole['admins'] / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- 5. TABLA: CANCHAS POPULARES --}}
                {{-- ========================================== --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2 text-xl">🏆</span> Canchas Más Populares
                    </h3>
                    @if($topCanchas->isEmpty())
                        <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                            <span class="text-3xl mb-2">📊</span>
                            <p class="text-sm">Aún no hay datos suficientes.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cancha</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Distrito</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Reservas</th>
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
                    @endif
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- 6. TABLA: ACTIVIDAD RECIENTE --}}
            {{-- ========================================== --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Últimas Reservas
                    </h3>
                    <a href="{{ route('admin.reports.reservas') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold hover:underline">Ver todas</a>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($recentReservas as $reserva)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($reserva->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $reserva->user->name ?? 'Usuario Eliminado' }}</div>
                                        <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $reserva->cancha->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">
                                        S/ {{ number_format($reserva->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->status == 'confirmed')
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">Confirmado</span>
                                        @elseif($reserva->status == 'pending')
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                        @else
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">Cancelado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay actividad reciente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS (Inicialización de Gráficos) --}}
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. Gráfico Principal (Lineal)
            const ctxMain = document.getElementById('mainChart');
            if (ctxMain) {
                new Chart(ctxMain.getContext('2d'), {
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                type: 'line',
                                label: 'Ingresos (S/)',
                                data: {!! json_encode($chartIncomeData) !!},
                                borderColor: '#10B981', // Green 500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y-income'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            'y-income': { type: 'linear', display: true, position: 'left' },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 2. Gráfico Usuarios (Pastel)
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
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>