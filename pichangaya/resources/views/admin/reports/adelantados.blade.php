<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <x-slot name="header">
        <header class="flex items-center gap-4 py-1">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 hover:shadow-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                         class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            <hgroup>
                <h1 class="text-xl font-black leading-tight text-blue-700 uppercase tracking-tighter sm:text-2xl">
                    Pagos <span class="text-gray-800">Adelantados</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Administración • Capital Disponible
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <article class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex justify-between items-center shadow-sm">
                    <hgroup>
                        <p class="text-sm font-bold text-blue-700 uppercase tracking-wide">Monto en Adelantos</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalAdelanto, 2) }}</p>
                    </hgroup>
                    <span class="p-4 bg-white rounded-full shadow-sm text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                </article>

                <article class="bg-white shadow-sm border border-gray-100 rounded-2xl p-4" 
                         x-data="{ init() { new Chart(document.getElementById('chartAdvance'), { type: 'bar', data: { labels: @js(array_keys($advanceByCancha)), datasets: [{ label: 'Adelantos (S/)', data: @js(array_values($advanceByCancha)), backgroundColor: '#3B82F6', borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: {display:false} }, x: { display: false } } } }); } }">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Adelantos por Cancha</h4>
                    <div class="h-32"><canvas id="chartAdvance"></canvas></div>
                </article>
            </section>

            <article class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden">
                <header class="px-6 py-4 border-b border-gray-100 bg-blue-50/50">
                    <h3 class="font-bold text-blue-800 uppercase text-sm">Reservas con Pago Adelantado</h3>
                </header>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Juego</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cancha</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Monto Total</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($adelantados as $reserva)
                                <tr class="hover:bg-blue-50/50 transition cursor-pointer group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="p-2 bg-gray-100 rounded-lg text-gray-500 group-hover:bg-blue-200 group-hover:text-blue-800 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </span>
                                            <hgroup>
                                                <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</div>
                                            </hgroup>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $reserva->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-blue-700">{{ $reserva->cancha->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-3 py-1 inline-flex text-sm font-black rounded-full bg-blue-100 text-blue-800">
                                            S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto group-hover:text-blue-600 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">No se registran pagos adelantados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</x-app-layout>