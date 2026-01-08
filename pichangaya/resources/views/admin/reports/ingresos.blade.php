<x-app-layout>
    {{-- Scripts necesarios (Chart.js) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-500 transition rounded-full hover:bg-gray-200" title="Volver al Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold leading-tight text-emerald-700">Reporte Financiero</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Invocamos al componente Livewire --}}
            @livewire('admin.reports.ingresos')
        </div>
    </div>
</x-app-layout>