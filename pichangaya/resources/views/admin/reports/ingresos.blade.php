{{-- resources/views/admin/reports/ingresos.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center gap-4">
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="p-2 text-gray-500 transition rounded-full hover:bg-emerald-100 hover:text-emerald-600" 
                   title="Volver al Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>
            <h1 class="text-xl font-black leading-tight text-emerald-800 uppercase tracking-tighter">
                Reporte Financiero
            </h1>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50">
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('admin.reports.ingresos')
        </section>
    </main>
</x-app-layout>