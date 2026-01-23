<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reports\usuarios.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <header class="flex items-center gap-4 py-1">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 hover:shadow-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Reporte <span class="text-blue-600">Usuarios</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Panel de Administración • Crecimiento de Comunidad
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">
            
            {{-- SECCIÓN: GRÁFICO DE CRECIMIENTO --}}
            <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                <h3 class="flex items-center mb-6 text-lg font-bold text-gray-800">
                    <div class="p-2 mr-3 bg-blue-100 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.5 4.5 6.75-6.75M2.25 12l6.75-6.75 4.5 4.5 6.75-6.75" />
                        </svg>
                    </div>
                    Registros en los últimos 6 meses
                </h3>
                <div class="h-80 w-full">
                    <canvas id="chartGrowth"></canvas>
                </div>
            </section>

            {{-- SECCIÓN: ROLES Y RESUMEN --}}
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                
                {{-- GRÁFICO DE ROLES --}}
                <section class="p-6 bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl md:col-span-1">
                    <h3 class="flex items-center justify-center mb-6 text-lg font-bold text-gray-800">
                        <div class="p-2 mr-3 bg-blue-50 rounded-lg text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                        Distribución de Roles
                    </h3>
                    <div class="h-64">
                        <canvas id="chartRoles"></canvas>
                    </div>
                </section>
                
                {{-- RESUMEN DE CARDS --}}
                <section class="flex flex-col justify-center p-8 bg-white border border-gray-100 shadow-xl sm:rounded-3xl md:col-span-2">
                    <header class="mb-6">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resumen de Cuentas Activas</h4>
                        <div class="h-1 w-12 bg-blue-500 mt-1 rounded-full"></div>
                    </header>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="group p-5 bg-blue-50/50 border border-blue-100 rounded-2xl transition hover:bg-blue-50">
                            <p class="text-4xl font-black text-blue-600 tracking-tighter">{{ $usersByRole['users'] }}</p>
                            <p class="text-xs font-bold text-blue-800 uppercase tracking-widest mt-1">Clientes</p>
                        </div>

                        <div class="group p-5 bg-emerald-50/50 border border-emerald-100 rounded-2xl transition hover:bg-emerald-50">
                            <p class="text-4xl font-black text-emerald-600 tracking-tighter">{{ $usersByRole['owners'] }}</p>
                            <p class="text-xs font-bold text-emerald-800 uppercase tracking-widest mt-1">Dueños</p>
                        </div>

                        <div class="group p-5 bg-rose-50/50 border border-rose-100 rounded-2xl transition hover:bg-rose-50">
                            <p class="text-4xl font-black text-rose-600 tracking-tighter">{{ $usersByRole['admins'] }}</p>
                            <p class="text-xs font-bold text-rose-800 uppercase tracking-widest mt-1">Admins</p>
                        </div>
                    </div>

                    <footer class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed italic">
                            Los datos reflejan el total de usuarios registrados y verificados en la plataforma hasta la fecha actual.
                        </p>
                    </footer>
                </section>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Growth Chart
            const ctxGrowth = document.getElementById('chartGrowth');
            if(ctxGrowth) {
                new Chart(ctxGrowth, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($growthLabels) !!},
                        datasets: [{ 
                            label: 'Nuevos Registros', 
                            data: {!! json_encode($growthData) !!}, 
                            borderColor: '#2563EB', 
                            backgroundColor: 'rgba(37, 99, 235, 0.08)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
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
                            x: { 
                                grid: { display: false },
                                ticks: { font: { weight: 'bold' } }
                            }
                        } 
                    }
                });
            }

            // Roles Chart
            const ctxRoles = document.getElementById('chartRoles');
            if(ctxRoles) {
                new Chart(ctxRoles, {
                    type: 'doughnut',
                    data: {
                        labels: ['Clientes', 'Dueños', 'Admins'],
                        datasets: [{
                            data: [{{ $usersByRole['users'] }}, {{ $usersByRole['owners'] }}, {{ $usersByRole['admins'] }}],
                            backgroundColor: ['#3B82F6', '#10B981', '#F43F5E'],
                            hoverOffset: 15,
                            borderWidth: 0
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: { 
                            legend: { 
                                position: 'bottom',
                                labels: { 
                                    usePointStyle: true, 
                                    padding: 20,
                                    font: { weight: 'bold', size: 11 } 
                                } 
                            } 
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>