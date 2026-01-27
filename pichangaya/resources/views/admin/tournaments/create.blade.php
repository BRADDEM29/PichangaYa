<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="font-black text-2xl text-gray-800 dark:text-white leading-tight">
                Configurar Nuevo Torneo
            </h2>
        </header>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.tournaments.store') }}" method="POST" id="tournamentForm" class="space-y-8">
                @csrf
                {{-- Input oculto que capturará la fecha/hora desde Livewire --}}
                <input type="hidden" name="start_date" id="final_start_date">

                {{-- PASO 1: INFORMACIÓN BÁSICA --}}
                <section class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                        <h3 class="flex items-center gap-2 font-bold text-gray-800 dark:text-white">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px]">1</span>
                            Información General
                        </h3>
                    </header>
                    
                    <fieldset class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Nombre del Torneo</label>
                                <input type="text" name="name" class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 py-3" placeholder="Ej: Copa de Verano 2024" required>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Sede del Evento</label>
                                <div class="relative">
                                    {{-- Usamos wire:model o un evento JS para decirle a Livewire qué cancha cargar --}}
                                    <select name="cancha_id" id="cancha_select" 
                                        onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))"
                                        class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 py-3 appearance-none shadow-sm" required>
                                        <option value="" disabled selected>Selecciona una cancha comercial...</option>
                                        @foreach($canchas as $cancha)
                                            <option value="{{ $cancha->id }}">{{ $cancha->name }} — S/ {{ $cancha->price_per_hour }}/h</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </section>

                {{-- PASO 2: HORARIO (TU CALENDARIO LIVEWIRE) --}}
                <section class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden border-t-4 border-t-indigo-600">
                    <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-indigo-50/30 dark:bg-indigo-900/10">
                        <h3 class="flex items-center gap-2 font-bold text-gray-800 dark:text-white">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px]">2</span>
                            Disponibilidad en Tiempo Real
                        </h3>
                    </header>
                    <div class="p-6">
                        <div class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-600 p-2 bg-white dark:bg-gray-800">
                            {{-- Llamamos a tu componente tal cual, pasándole la primera cancha por defecto --}}
                            @livewire('cancha-reserva-form', ['cancha' => $canchas->first()])
                        </div>
                    </div>
                </section>

                {{-- PASO 3: EQUIPOS --}}
                <section class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex justify-between items-center">
                        <h3 class="flex items-center gap-2 font-bold text-gray-800 dark:text-white">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px]">3</span>
                            Participantes
                        </h3>
                        <output id="teamCounter" class="text-xs font-black bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full">0</output>
                    </header>
                    
                    <div class="p-6">
                        <div id="teamsContainer" class="grid grid-cols-1 gap-3 mb-6">
                            {{-- JS Inyecta inputs aquí --}}
                        </div>

                        <nav class="flex gap-3">
                            <button type="button" onclick="addTeamInput()" class="flex-1 py-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-2xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold text-sm transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Añadir Equipo
                            </button>
                            <button type="button" onclick="addTeamInput(true)" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl text-gray-400 font-medium text-sm hover:text-indigo-600 transition-colors">
                                + Bye
                            </button>
                        </nav>
                    </div>
                </section>

                <footer class="pt-4">
                    <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xl rounded-3xl shadow-xl shadow-indigo-200 dark:shadow-none transition-all transform active:scale-[0.98] flex items-center justify-center gap-4">
                        <span>CREAR TORNEO AHORA</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </footer>
            </form>
        </main>
    </div>

    <script>
        // 1. ESCUCHAR EL SELECT DE SEDE PARA ACTUALIZAR LIVEWIRE
        window.addEventListener('cancha-changed', event => {
            const canchaId = event.detail.id;
            // Emitimos evento a Livewire para que cargue la nueva cancha
            // Nota: Asegúrate que tu componente Livewire tenga un listener para esto o usa una prop reactiva
            Livewire.dispatch('updateCancha', { id: canchaId });
        });

        // 2. CAPTURAR LA SELECCIÓN DEL CALENDARIO
        // Tu componente Livewire debe emitir este evento al seleccionar una hora
        window.addEventListener('time-selected', event => {
            const { date, time } = event.detail;
            document.getElementById('final_start_date').value = `${date} ${time}`;
            
            // Opcional: Feedback visual de que se seleccionó la hora
            console.log("Horario seleccionado para el torneo:", date, time);
        });

        // Lógica de equipos original
        document.addEventListener('DOMContentLoaded', () => {
            for(let i=0; i<4; i++) addTeamInput();
        });

        function addTeamInput(isBye = false) {
            const container = document.getElementById('teamsContainer');
            const section = document.createElement('div');
            section.className = "flex items-center gap-3 animate-in slide-in-from-top-1 duration-200";
            const inputClass = "w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-indigo-500 py-3 shadow-sm font-bold";
            const content = isBye 
                ? `<input type="text" name="teams[]" value="BYE (DESCANSO)" readonly class="${inputClass} bg-gray-50 text-gray-400 italic cursor-not-allowed">`
                : `<input type="text" name="teams[]" required placeholder="Nombre del equipo..." class="${inputClass}">`;

            section.innerHTML = `
                <div class="flex-1 relative group">
                    ${content}
                    <div class="absolute left-[-12px] top-1/2 -translate-y-1/2 w-1.5 h-6 bg-indigo-100 dark:bg-indigo-900 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <button type="button" onclick="this.parentElement.remove(); updateCounter();" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(section);
            updateCounter();
        }

        function updateCounter() {
            document.getElementById('teamCounter').value = document.getElementById('teamsContainer').children.length;
        }
    </script>
</x-app-layout>