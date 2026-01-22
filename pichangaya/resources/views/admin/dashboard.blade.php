<x-app-layout>
    {{-- resources/views/admin/dashboard.blade.php --}}
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <x-slot name="header">
        <header class="flex items-center gap-3 py-2">
            <figure class="bg-gray-800 p-2 rounded-lg text-white shadow-md flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </figure>
            <h1 class="font-black text-xl md:text-2xl text-gray-800 leading-tight tracking-tight uppercase">
                {{ __('Panel de Control General') }}
            </h1>
        </header>
    </x-slot>

    <main class="py-6 md:py-12 bg-gray-50 min-h-screen">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-10">
            
            {{-- 1. KPI CARDS - Responsive Nav --}}
            <nav aria-label="Métricas financieras clave" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                {{-- Card: Ingresos --}}
                <a href="{{ route('admin.reports.ingresos') }}" class="group transition-transform hover:-translate-y-1">
                    <article class="bg-white shadow-sm rounded-2xl p-5 border-l-[6px] border-emerald-500 flex items-center justify-between group-hover:bg-emerald-50/50 transition-colors">
                        <header>
                            <h2 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Pagos Completos</h2>
                            <data value="{{ $ingresosTotales }}" class="text-2xl font-black text-gray-900 leading-none">S/ {{ number_format($ingresosTotales, 2) }}</data>
                            <footer class="text-[11px] text-gray-400 mt-2 font-bold uppercase tracking-tighter">Ver detalles →</footer>
                        </header>
                        <figure class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </figure>
                    </article>
                </a>

                {{-- Card: Adelantos --}}
                <a href="{{ route('admin.reports.adelantados') }}" class="group transition-transform hover:-translate-y-1">
                    <article class="bg-white shadow-sm rounded-2xl p-5 border-l-[6px] border-blue-500 flex items-center justify-between group-hover:bg-blue-50/50 transition-colors">
                        <header>
                            <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Adelantos</h2>
                            <data value="{{ $adelantosTotal }}" class="text-2xl font-black text-gray-900 leading-none">S/ {{ number_format($adelantosTotal, 2) }}</data>
                            <footer class="text-[11px] text-blue-500 mt-2 font-bold uppercase tracking-tighter">{{ $adelantosCount }} Reservas</footer>
                        </header>
                        <figure class="p-2.5 rounded-xl bg-blue-50 text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </figure>
                    </article>
                </a>

                {{-- Card: Pendientes --}}
                <a href="{{ route('admin.reports.pendientes') }}" class="group transition-transform hover:-translate-y-1">
                    <article class="bg-white shadow-sm rounded-2xl p-5 border-l-[6px] border-amber-400 flex items-center justify-between group-hover:bg-amber-50/50 transition-colors">
                        <header>
                            <h2 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Por Cobrar</h2>
                            <data value="{{ $pendientesMoney }}" class="text-2xl font-black text-gray-900 leading-none">S/ {{ number_format($pendientesMoney, 2) }}</data>
                            <footer class="text-[11px] text-amber-600 font-bold mt-2 uppercase tracking-tighter">{{ $pendientesCount }} Pendientes</footer>
                        </header>
                        <figure class="p-2.5 rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </figure>
                    </article>
                </a>

                {{-- Card: Perdido --}}
                <a href="{{ route('admin.reports.cancelados') }}" class="group transition-transform hover:-translate-y-1">
                    <article class="bg-white shadow-sm rounded-2xl p-5 border-l-[6px] border-red-500 flex items-center justify-between group-hover:bg-red-50/50 transition-colors">
                        <header>
                            <h2 class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Ingreso Perdido</h2>
                            <data value="{{ $canceladosMoney }}" class="text-2xl font-black text-gray-900 leading-none">S/ {{ number_format($canceladosMoney, 2) }}</data>
                            <footer class="text-[11px] text-red-600 font-bold mt-2 uppercase tracking-tighter">{{ $canceladosCount }} Caídas</footer>
                        </header>
                        <figure class="p-2.5 rounded-xl bg-red-50 text-red-600 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </figure>
                    </article>
                </a>
            </nav>

            {{-- 2. ACCESOS RÁPIDOS - Operativos --}}
            <nav aria-label="Accesos rápidos de gestión" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <a href="{{ route('admin.reports.reservas') }}" class="group bg-white shadow-sm p-5 rounded-2xl flex items-center border border-gray-100 hover:border-indigo-200 transition-all">
                    <figure class="p-3 bg-indigo-50 rounded-xl text-indigo-600 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </figure>
                    <hgroup>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Total Histórico</p>
                        <p class="text-lg font-black text-gray-800">{{ $reservasTotales }} Reservas</p>
                    </hgroup>
                </a>
                <a href="{{ route('admin.reports.usuarios') }}" class="group bg-white shadow-sm p-5 rounded-2xl flex items-center border border-gray-100 hover:border-blue-200 transition-all">
                    <figure class="p-3 bg-blue-50 rounded-xl text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </figure>
                    <hgroup>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Base Usuarios</p>
                        <p class="text-lg font-black text-gray-800">{{ $totalUsers }} Registrados</p>
                    </hgroup>
                </a>
                <a href="{{ route('admin.reports.canchas') }}" class="group bg-white shadow-sm p-5 rounded-2xl flex items-center border border-gray-100 hover:border-amber-200 transition-all">
                    <figure class="p-3 bg-amber-50 rounded-xl text-amber-600 mr-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </figure>
                    <hgroup>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Inventario</p>
                        <p class="text-lg font-black text-gray-800">{{ $totalCanchas }} Canchas</p>
                    </hgroup>
                </a>
            </nav>

            {{-- 3. TENDENCIA FINANCIERA --}}
            <section class="bg-white shadow-sm rounded-3xl p-6 md:p-8 border border-gray-100">
                <header class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-6">
                    <h2 class="text-lg font-black text-gray-900 flex items-center uppercase tracking-tighter">
                        <span class="w-2 h-6 bg-indigo-600 rounded-full mr-3"></span>
                        Tendencia de Ingresos
                    </h2>
                    <ul class="flex flex-wrap gap-3 md:gap-5 text-[10px] font-black bg-gray-50 p-3 rounded-2xl border border-gray-100 list-none">
                        <li class="flex items-center"><span class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></span> PAGADO</li>
                        <li class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> ADELANTO</li>
                        <li class="flex items-center"><span class="w-3 h-3 bg-amber-400 rounded-full mr-2"></span> PENDIENTE</li>
                        <li class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> PERDIDO</li>
                    </ul>
                </header>
                @livewire('admin.financial-chart')
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">
                
                {{-- 4. USUARIOS POR ROL --}}
                <aside class="bg-white shadow-sm rounded-3xl p-6 border border-gray-100">
                    <header class="border-b border-gray-50 pb-4 mb-6">
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest">Segmentación</h2>
                    </header>
                    
                    <section class="flex flex-row lg:flex-col items-center gap-6">
                        <figure class="w-24 h-24 flex-shrink-0 relative">
                            <canvas id="userRoleChart"></canvas>
                        </figure>

                        <section class="flex-1 w-full space-y-4">
                            @foreach([
                                ['label' => 'Clientes', 'count' => $usersByRole['users'], 'color' => 'bg-blue-600', 'p' => ($usersByRole['users']/max($totalUsers,1))*100],
                                ['label' => 'Dueños', 'count' => $usersByRole['owners'], 'color' => 'bg-emerald-500', 'p' => ($usersByRole['owners']/max($totalUsers,1))*100],
                                ['label' => 'Admins', 'count' => $usersByRole['admins'], 'color' => 'bg-red-500', 'p' => ($usersByRole['admins']/max($totalUsers,1))*100]
                            ] as $role)
                            <article>
                                <header class="flex justify-between items-end mb-1">
                                    <span class="text-[10px] font-black text-gray-500 uppercase">{{ $role['label'] }}</span>
                                    <span class="text-xs font-black text-gray-900">{{ $role['count'] }}</span>
                                </header>
                                <progress value="{{ $role['p'] }}" max="100" class="w-full h-1.5 rounded-full overflow-hidden block appearance-none">
                                    <span class="{{ $role['color'] }} h-full block" style="width: {{ $role['p'] }}%"></span>
                                </progress>
                                <style>
                                    progress::-webkit-progress-bar { background-color: #f3f4f6; border-radius: 999px; }
                                    progress::-webkit-progress-value { background-color: {{ str_replace('bg-', '', $role['color']) == 'blue-600' ? '#2563eb' : (str_replace('bg-', '', $role['color']) == 'emerald-500' ? '#10b981' : '#ef4444') }}; border-radius: 999px; }
                                </style>
                            </article>
                            @endforeach
                        </section>
                    </section>
                </aside>

                {{-- 5. CANCHAS POPULARES --}}
                <section class="bg-white shadow-sm rounded-3xl p-6 lg:col-span-2 border border-gray-100 overflow-hidden">
                    <header class="mb-6">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Top Sedes Populares
                        </h2>
                    </header>
                    <section class="overflow-x-auto rounded-xl">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <th class="pb-3 px-2">Cancha</th>
                                    <th class="pb-3 px-2">Sede</th>
                                    <th class="pb-3 px-2 text-right">Reservas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($topCanchas as $cancha)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-2 text-xs font-bold text-gray-800">{{ $cancha->name }}</td>
                                    <td class="py-4 px-2 text-xs text-gray-400 italic">{{ $cancha->district->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-2 text-right font-black text-indigo-600 text-xs">{{ $cancha->reservas_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                </section>
            </section>

            {{-- 6. ÚLTIMAS RESERVAS --}}
            <section class="bg-white shadow-sm rounded-3xl p-4 md:p-8 border border-gray-100">
                <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-tighter flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Actividad en Vivo
                    </h2>
                    <nav>
                        <a href="{{ route('admin.reports.reservas') }}" class="text-[10px] font-black text-white bg-gray-900 px-4 py-2.5 rounded-xl hover:bg-indigo-600 transition-all uppercase tracking-widest">Historial Completo</a>
                    </nav>
                </header>
                
                <section class="overflow-x-auto -mx-4 md:mx-0">
                    <table class="w-full min-w-[700px] text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="px-6 py-4">Tiempo</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Cancha</th>
                                <th class="px-6 py-4">Monto</th>
                                <th class="px-6 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentReservas as $reserva)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-tighter">{{ $reserva->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-5">
                                    <hgroup class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900">{{ $reserva->user->name ?? 'Invitado' }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $reserva->user->email ?? '' }}</span>
                                    </hgroup>
                                </td>
                                <td class="px-6 py-5 text-xs text-gray-600 font-medium">{{ $reserva->cancha->name ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm font-black text-gray-900">S/ {{ number_format($reserva->total_price, 2) }}</td>
                                <td class="px-6 py-5 text-right">
                                    <span class="px-3 py-1 text-[9px] font-black rounded-lg uppercase bg-gray-100 text-gray-500 ring-1 ring-gray-200 group-hover:bg-white transition-colors">
                                        {{ $reserva->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Sin datos recientes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </section>
        </section>
    </main>

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
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '80%', 
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>