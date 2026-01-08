<x-app-layout>
    {{-- resources/views/admin/reports/cancelados.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-500 transition rounded-full hover:bg-gray-200" title="Volver al Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold leading-tight text-red-700">Reporte de Reservas Canceladas</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- GRID DE TARJETAS (INFO + GRÁFICO) --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- CARD 1: TOTAL PERDIDO --}}
                <div class="flex items-center justify-between p-6 border border-red-200 bg-red-50 rounded-xl relative overflow-hidden">
                    <div class="z-10">
                        <p class="text-xs font-black tracking-widest text-red-600 uppercase mb-1">Ingreso Perdido Total</p>
                        <p class="text-4xl font-black text-gray-800">S/ {{ number_format($totalPerdido, 2) }}</p>
                    </div>
                    <div class="p-4 text-red-600 bg-white rounded-full shadow-sm z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    {{-- Decoración de fondo --}}
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-red-100 rounded-full opacity-50"></div>
                </div>

                {{-- CARD 2: GRÁFICO DONUT --}}
                <div class="p-5 bg-white shadow rounded-xl border border-gray-100"
                     x-data="{
                        init() {
                            new Chart(document.getElementById('chartLost'), {
                                type: 'doughnut',
                                data: {
                                    labels: @js(array_keys($lostByDistrict)),
                                    datasets: [{
                                        data: @js(array_values($lostByDistrict)),
                                        backgroundColor: ['#EF4444', '#F87171', '#FCA5A5', '#FECACA', '#FEE2E2'],
                                        borderWidth: 0,
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { position: 'right', labels: { boxWidth: 12, font: {size: 11}, padding: 20 } }
                                    },
                                    layout: { padding: 5 }
                                }
                            });
                        }
                     }">
                    <h4 class="mb-3 text-xs font-bold text-gray-400 uppercase tracking-wide">Pérdida por Distrito</h4>
                    <div class="h-32"><canvas id="chartLost"></canvas></div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl ring-1 ring-gray-900/5">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white flex justify-between items-center">
                    <h3 class="font-bold text-red-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Historial de Cancelaciones
                    </h3>
                    <span class="text-xs text-gray-400 italic">Click en la fila para gestionar</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">Cancelado El</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">Fecha Juego (Original)</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase tracking-wider">Cancha / Distrito</th>
                                <th class="px-6 py-3 text-xs font-bold text-right text-gray-500 uppercase tracking-wider">Monto Perdido</th>
                                <th class="px-6 py-3 text-xs font-bold text-center text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($cancelados as $reserva)
                                {{-- LOGICA DE CLIC: Redirige a la lista de reservas de esa cancha específica --}}
                                <tr class="transition cursor-pointer hover:bg-red-50 group"
                                    onclick="window.location='{{ route('admin.canchas.reservas.index', $reserva->cancha) }}'">
                                    
                                    {{-- 1. Fecha y Hora de Cancelación --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 text-red-500 transition bg-red-100 rounded-lg group-hover:bg-white group-hover:shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-700">
                                                    {{ $reserva->updated_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-red-400 font-medium">
                                                    {{ $reserva->updated_at->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 2. Fecha Original del Juego --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($reserva->start_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 3. Cliente --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold border border-gray-200">
                                                {{ substr($reserva->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'Usuario Eliminado' }}</div>
                                                <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 4. Cancha --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700 group-hover:text-red-700 transition">{{ $reserva->cancha->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $reserva->cancha->district->name ?? 'Sin distrito' }}
                                        </div>
                                    </td>

                                    {{-- 5. Monto --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-sm font-black text-red-700 rounded-full bg-red-100 group-hover:bg-white group-hover:shadow-sm transition border border-transparent group-hover:border-red-100">
                                            - S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>

                                    {{-- 6. Flecha indicadora --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto transition-transform duration-200 transform group-hover:text-red-500 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-white">
                                        <div class="flex flex-col items-center">
                                            <div class="p-3 bg-gray-50 rounded-full mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-500">No hay reservas canceladas en el registro.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>