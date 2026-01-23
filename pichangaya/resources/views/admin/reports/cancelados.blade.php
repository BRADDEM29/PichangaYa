<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <header class="flex items-center gap-4 py-1">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-red-50 hover:border-red-200 hover:text-red-600 hover:shadow-sm shadow-sm" 
                   title="Volver al Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                         class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            <hgroup>
                <h1 class="text-xl font-black leading-tight text-red-700 uppercase tracking-tighter sm:text-2xl">
                    Reporte de <span class="text-gray-800">Cancelaciones</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Administración • Control de Pérdidas
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <section class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <article class="flex items-center justify-between p-6 border border-red-200 bg-red-50 rounded-2xl relative overflow-hidden shadow-sm">
                    <hgroup class="z-10">
                        <p class="text-xs font-black tracking-widest text-red-600 uppercase mb-1">Ingreso Perdido Total</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalPerdido, 2) }}</p>
                    </hgroup>
                    <span class="p-4 text-red-600 bg-white rounded-full shadow-sm z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </span>
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-red-100 rounded-full opacity-50" aria-hidden="true"></div>
                </article>

                <article class="p-5 bg-white shadow-sm rounded-2xl border border-gray-100"
                         x-data="{ init() { new Chart(document.getElementById('chartLost'), { type: 'doughnut', data: { labels: @js(array_keys($lostByDistrict)), datasets: [{ data: @js(array_values($lostByDistrict)), backgroundColor: ['#EF4444', '#F87171', '#FCA5A5', '#FECACA', '#FEE2E2'], borderWidth: 0, hoverOffset: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { boxWidth: 12, font: {size: 11}, padding: 20 } } } } }); } }">
                    <h4 class="mb-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Pérdida por Distrito</h4>
                    <div class="h-32"><canvas id="chartLost"></canvas></div>
                </article>
            </section>

            <article class="overflow-hidden bg-white shadow-sm sm:rounded-3xl border border-gray-100">
                <header class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white flex justify-between items-center">
                    <h3 class="font-bold text-red-800 uppercase text-sm tracking-wider flex items-center gap-2">
                        Historial de Cancelaciones
                    </h3>
                </header>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left text-gray-500 uppercase tracking-wider">Fecha Cancelación</th>
                                <th class="px-6 py-3 font-bold text-left text-gray-500 uppercase tracking-wider">Fecha Juego Original</th>
                                <th class="px-6 py-3 font-bold text-left text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 font-bold text-left text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 font-bold text-right text-gray-500 uppercase tracking-wider">Monto Perdido</th>
                                <th class="px-6 py-3 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($cancelados as $reserva)
                                <tr class="transition cursor-pointer hover:bg-red-50/50 group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="p-2 text-red-500 transition bg-red-100 rounded-lg group-hover:bg-white group-hover:shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </span>
                                            <hgroup>
                                                <div class="font-bold text-gray-700">{{ $reserva->updated_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-red-400 font-medium">{{ $reserva->updated_at->format('H:i') }}</div>
                                            </hgroup>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $reserva->user->name ?? 'Usuario N/A' }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-700 group-hover:text-red-700">{{ $reserva->cancha->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $reserva->cancha->district->name ?? '' }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 font-black text-red-700 rounded-full bg-red-100">
                                            - S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto transition-transform group-hover:text-red-500 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No se registran reservas canceladas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</x-app-layout>