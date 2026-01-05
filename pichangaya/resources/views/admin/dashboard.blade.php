<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\dashboard.blade.php --}}
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-gray-800 p-2 rounded-lg text-white shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Panel de Control General') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- VERDE: PAGADOS --}}
                <a href="{{ route('admin.reports.ingresos') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-6 border-l-8 border-emerald-500 flex items-center justify-between group-hover:bg-emerald-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Pagos Completos</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($ingresosTotales, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1 font-bold group-hover:text-emerald-700">Ver detalles <span class="text-lg">→</span></p>
                        </div>
                        <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                </a>
                {{-- AZUL: ADELANTOS --}}
                <a href="{{ route('admin.reports.adelantados') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-6 border-l-8 border-blue-500 flex items-center justify-between group-hover:bg-blue-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Pagos Adelantados</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($adelantosTotal, 2) }}</p>
                            <p class="text-xs text-blue-600 mt-2 flex items-center gap-1 font-bold group-hover:text-blue-800">{{ $adelantosCount }} Reservas <span class="text-lg">→</span></p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                    </div>
                </a>
                {{-- AMARILLO: PENDIENTES --}}
                <a href="{{ route('admin.reports.pendientes') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-6 border-l-8 border-amber-400 flex items-center justify-between group-hover:bg-amber-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-1">Por Cobrar</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($pendientesMoney, 2) }}</p>
                            <p class="text-xs text-amber-600 font-bold mt-2">{{ $pendientesCount }} Reservas en espera</p>
                        </div>
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                </a>
                {{-- ROJO: CANCELADOS --}}
                <a href="{{ route('admin.reports.cancelados') }}" class="block group transform transition hover:-translate-y-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl p-6 border-l-8 border-red-500 flex items-center justify-between group-hover:bg-red-50 transition-colors">
                        <div>
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Ingreso Perdido</p>
                            <p class="text-3xl font-black text-gray-800">S/ {{ number_format($canceladosMoney, 2) }}</p>
                            <p class="text-xs text-red-600 font-bold mt-2">{{ $canceladosCount }} Reservas caídas</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 text-red-600 group-hover:scale-110 transition-transform"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
                    </div>
                </a>
            </div>

            {{-- 2. ACCESOS RÁPIDOS (OPERATIVO) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.reports.reservas') }}" class="block group bg-white shadow-md hover:shadow-lg p-5 rounded-xl flex items-center border border-gray-100 hover:border-indigo-300 transition-all hover:-translate-y-0.5">
                    <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Total Histórico</p><p class="text-xl font-bold text-gray-800">{{ $reservasTotales }} Reservas</p></div>
                </a>
                <a href="{{ route('admin.reports.usuarios') }}" class="block group bg-white shadow-md hover:shadow-lg p-5 rounded-xl flex items-center border border-gray-100 hover:border-blue-300 transition-all hover:-translate-y-0.5">
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Base de Usuarios</p><p class="text-xl font-bold text-gray-800">{{ $totalUsers }} Registrados</p></div>
                </a>
                <a href="{{ route('admin.reports.canchas') }}" class="block group bg-white shadow-md hover:shadow-lg p-5 rounded-xl flex items-center border border-gray-100 hover:border-yellow-300 transition-all hover:-translate-y-0.5">
                    <div class="p-3 bg-amber-50 rounded-lg text-amber-600 mr-4 group-hover:bg-amber-600 group-hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Inventario</p><p class="text-xl font-bold text-gray-800">{{ $totalCanchas }} Canchas</p></div>
                </a>
            </div>

            {{-- 3. GRÁFICO TENDENCIA FINANCIERA --}}
            <div class="bg-white shadow-xl sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Tendencia Financiera (Últimos 15 días)
                    </h3>
                    {{-- Leyenda --}}
                    <div class="flex flex-wrap gap-4 text-xs font-bold bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                        <span class="flex items-center"><span class="w-3 h-3 bg-emerald-500 rounded-full mr-1.5 shadow-sm"></span> Pagado</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-1.5 shadow-sm"></span> Adelanto</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-amber-400 rounded-full mr-1.5 shadow-sm"></span> Pendiente</span>
                        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1.5 shadow-sm"></span> Perdido</span>
                    </div>
                </div>
                
                {{-- COMPONENTE GRÁFICO AISLADO --}}
                @livewire('admin.financial-chart')
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- 🔴 4. USUARIOS POR ROL (CORREGIDO: TODO PEGADO ARRIBA) --}}
                <div class="bg-white shadow-xl sm:rounded-2xl p-6 border border-gray-100 flex flex-col justify-start h-auto">
                    {{-- Título con borde inferior y poco margen --}}
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-2 mb-4">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Usuarios por Rol
                    </h3>
                    
                    {{-- Contenedor Flex alineado al inicio (items-start) --}}
                    <div class="flex flex-row items-start gap-4">
                        
                        {{-- Gráfico circular (Compacto) --}}
                        <div class="w-24 h-24 flex-shrink-0 relative mt-1">
                            <canvas id="userRoleChart"></canvas>
                        </div>

                        {{-- Lista de Datos (Pegada justo después del gráfico) --}}
                        <div class="flex-1 w-full space-y-4">
                            @php
                                $total = max($totalUsers, 1);
                                $userP = ($usersByRole['users'] / $total) * 100;
                                $ownerP = ($usersByRole['owners'] / $total) * 100;
                                $adminP = ($usersByRole['admins'] / $total) * 100;
                            @endphp
                            
                            {{-- Clientes --}}
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Clientes</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $usersByRole['users'] }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $userP }}%"></div>
                                </div>
                            </div>

                            {{-- Dueños --}}
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Dueños</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $usersByRole['owners'] }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $ownerP }}%"></div>
                                </div>
                            </div>

                            {{-- Admins --}}
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Admins</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $usersByRole['admins'] }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $adminP }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. CANCHAS POPULARES --}}
                <div class="bg-white shadow-xl sm:rounded-2xl p-6 lg:col-span-2 border border-gray-100 h-auto self-start">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Top Canchas Populares
                    </h3>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
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
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $cancha->district->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-black text-indigo-600 bg-indigo-50/50">{{ $cancha->reservas_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 6. ÚLTIMAS RESERVAS (TABLA) --}}
            <div class="bg-white shadow-xl sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Últimas Reservas
                    </h3>
                    <a href="{{ route('admin.reports.reservas') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold hover:underline flex items-center gap-1">
                        Ver todas
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center gap-1">
                                        {{ $reserva->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->user)
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900">{{ $reserva->user->name }}</span>
                                                <span class="text-xs text-gray-400">{{ $reserva->user->email }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-red-500 font-bold italic">Usuario purgado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reserva->cancha->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">S/ {{ number_format($reserva->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                            {{ $reserva->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No hay actividad reciente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (Solo para el Donut, el Line Chart ya viene en el componente x-admin.financial-chart) --}}
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctxUser = document.getElementById('userRoleChart');
            if (ctxUser) {
                new Chart(ctxUser.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Clientes', 'Dueños', 'Admins'],
                        datasets: [{
                            data: [{{ $usersByRole['users'] }}, {{ $usersByRole['owners'] }}, {{ $usersByRole['admins'] }}],
                            backgroundColor: ['#2563EB', '#10B981', '#EF4444'], 
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%', 
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false } // Tooltip desactivado para vista más limpia
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>