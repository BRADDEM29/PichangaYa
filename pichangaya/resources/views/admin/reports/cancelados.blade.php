<x-app-layout>
    {{-- resources/views/admin/reports/cancelados.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <div class="flex items-center gap-4">
<<<<<<< HEAD
            <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 transition text-gray-500">← Volver</a>
            <h2 class="font-semibold text-xl text-red-700 leading-tight">Reporte de Reservas Canceladas</h2>
=======
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-400 transition rounded-full hover:bg-gray-100 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold leading-tight text-red-700">Reporte de Reservas Canceladas</h2>
>>>>>>> e1292eb26a868daadef5581f52dd506c19d198df
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- INFO CARD --}}
                <div class="flex items-center justify-between p-6 border border-red-200 bg-red-50 rounded-2xl">
                    <div>
                        <p class="text-sm font-bold tracking-wider text-red-700 uppercase">Ingreso Perdido Total</p>
                        <p class="mt-2 text-4xl font-black text-gray-800">S/ {{ number_format($totalPerdido, 2) }}</p>
                    </div>
                    <div class="p-4 bg-white rounded-full shadow-sm text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
<<<<<<< HEAD
                    <span class="text-4xl"></span>
=======
>>>>>>> e1292eb26a868daadef5581f52dd506c19d198df
                </div>

                {{-- GRÁFICO DONUT CON ALPINE --}}
                <div class="p-5 bg-white shadow rounded-2xl border border-gray-100"
                     x-data="{
                        init() {
                            new Chart(document.getElementById('chartLost'), {
                                type: 'doughnut',
                                data: {
                                    labels: @js(array_keys($lostByDistrict)),
                                    datasets: [{
                                        data: @js(array_values($lostByDistrict)),
                                        backgroundColor: ['#EF4444', '#F87171', '#FCA5A5', '#FECACA'],
                                        borderWidth: 0
                                    }]
                                },
                                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { boxWidth: 10, font: {size: 10} } } } }
                            });
                        }
                     }">
                    <h4 class="mb-3 text-xs font-bold text-gray-400 uppercase">Pérdida por Distrito</h4>
                    <div class="h-32"><canvas id="chartLost"></canvas></div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-700">Historial de Cancelaciones</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Fecha Cancelación</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Cancha / Dueño</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-right text-gray-500 uppercase">Monto Perdido</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($cancelados as $reserva)
                                <tr class="transition hover:bg-red-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800">{{ $reserva->updated_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">Original: {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reserva->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $reserva->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-700">{{ $reserva->cancha->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reserva->cancha->district->name ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-sm font-black text-red-800 rounded-full bg-red-100">
                                            - S/ {{ number_format($reserva->total_price, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay reservas canceladas recientemente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>