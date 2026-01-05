<x-app-layout>
    {{-- Carga de Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        {{-- Dejamos el header vacío o simple porque el componente ya tiene título --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- LLAMAMOS AL COMPONENTE LIVEWIRE --}}
            @livewire('admin.reports.ingresos')
        </div>
    </div>
</x-app-layout>