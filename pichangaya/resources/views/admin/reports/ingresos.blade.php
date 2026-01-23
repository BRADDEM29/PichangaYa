{{-- resources/views/admin/reports/ingresos.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- header define la cabecera de la sección --}}
        <header class="flex items-center gap-4 py-1">
            
            {{-- nav para elementos de navegación --}}
            <nav aria-label="Navegación de retorno">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 text-gray-600 transition-all duration-300 rounded-xl hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 hover:shadow-sm shadow-sm" 
                   title="Volver al Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                         class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </nav>

            {{-- span con aria-hidden para elementos puramente decorativos --}}
            <span class="h-8 w-px bg-gray-200 hidden sm:block" aria-hidden="true"></span>

            {{-- hgroup para agrupar títulos y subtítulos relacionados --}}
            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Reporte <span class="text-emerald-600">Financiero</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Panel de Administración • Ingresos
                </p>
            </hgroup>

        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        {{-- section representa una sección genérica de contenido --}}
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- article para contenido independiente (el reporte en sí) --}}
            <article class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                @livewire('admin.reports.ingresos')
            </article>

        </section>
    </main>
</x-app-layout>