<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-xl border border-purple-200 dark:border-purple-800">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h1 class="font-black text-2xl text-gray-800 dark:text-white leading-tight">
                    Crear Nuevo Torneo
                </h1>
            </div>
        </header>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- 
                🟢 ESTRUCTURA FLEXBOX INTELIGENTE:
                - En móvil es una columna única (flex-col).
                - En escritorio es una fila (flex-row) donde la sidebar ocupa 1/3.
            --}}
            <form action="{{ route('admin.tournaments.store') }}" method="POST" id="tournamentForm" class="flex flex-col lg:flex-row gap-8">
                @csrf
                
                {{-- 
                    🟢 WRAPPER SIDEBAR (IZQUIERDA):
                    - 'contents': En móvil este div es invisible para el DOM, sus hijos obedecen al 'order' global.
                    - 'lg:flex': En escritorio se comporta como una columna sólida (Info + Botón pegados).
                --}}
                <aside class="contents lg:flex lg:flex-col lg:w-1/3 gap-6 h-fit sticky top-8">
                    
                    {{-- 1. INFO (Móvil: Primer lugar) --}}
                    <section class="order-1 lg:order-none bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <header class="mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Detalles del Evento
                            </h3>
                        </header>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre del Torneo</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-purple-500 focus:border-purple-500 font-medium shadow-sm transition-shadow focus:shadow-md" placeholder="Ej: Copa Verano 2024" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Sede (Cancha)</label>
                                <div class="relative">
                                    <select name="cancha_id" id="cancha_select" 
                                        onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))"
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-purple-500 focus:border-purple-500 font-medium shadow-sm appearance-none cursor-pointer" required>
                                        @foreach($canchas as $cancha)
                                            <option value="{{ $cancha->id }}" {{ old('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                                {{ $cancha->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 3. ACCIONES Y RESUMEN (Móvil: Tercer lugar - AL FINAL) --}}
                    {{-- En escritorio, esto aparecerá justo debajo de la tarjeta de Info --}}
                    <div class="order-3 lg:order-none space-y-6">
                        
                        {{-- Tarjeta Resumen Flotante --}}
                        <div id="selectionSummary" class="hidden bg-purple-600 rounded-2xl shadow-lg border border-purple-700 p-6 text-white transform transition-all duration-500 hover:scale-[1.02] ring-4 ring-purple-50 dark:ring-purple-900/20">
                            <h4 class="text-purple-200 text-[10px] font-bold uppercase tracking-widest mb-3 pb-2 border-b border-purple-500/30 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Bloqueo Seleccionado
                            </h4>
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-5xl font-black block leading-none tracking-tighter" id="summaryDuration">0</span>
                                    <span class="text-sm font-medium text-purple-200">Horas</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-2xl font-bold tracking-tight" id="summaryTime">--:--</span>
                                    <span class="text-xs text-purple-200 font-medium opacity-80 block mt-1" id="summaryDate">--/--/--</span>
                                </div>
                            </div>
                            
                            <input type="hidden" name="start_date" id="final_start_date">
                            <input type="hidden" name="duration" id="final_duration">
                        </div>

                        {{-- Botón Submit (Grande y vistoso) --}}
                        <button type="submit" id="submitBtn" disabled class="w-full py-4 px-6 bg-gray-200 text-gray-400 font-black text-lg rounded-2xl cursor-not-allowed transition-all flex items-center justify-center gap-3 group shadow-sm border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-600">
                            <span>Selecciona horario</span>
                        </button>
                    </div>

                </aside>

                {{-- 2. CONTENIDO PRINCIPAL (Móvil: Segundo lugar - AL MEDIO) --}}
                <div class="order-2 lg:order-none lg:w-2/3 space-y-6">
                    
                    {{-- Tarjeta Livewire --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/20">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Disponibilidad
                            </h3>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 uppercase tracking-wide border border-purple-100 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                                </span>
                                Modo Torneo
                            </span>
                        </header>
                        
                        <div class="p-6 bg-white dark:bg-gray-900/50">
                            <style>
                                .livewire-cancha-form .border-t.border-dashed { display: none !important; }
                            </style>
                            <div class="livewire-cancha-form">
                                @livewire('cancha-reserva-form', [
                                    'cancha' => $canchas->first(), 
                                    'isTournamentMode' => true
                                ])
                            </div>
                        </div>
                    </section>

                    {{-- Tarjeta Equipos --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <header class="flex justify-between items-center mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Participantes
                            </h3>
                            <span id="teamCountBadge" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200 dark:border-gray-600 shadow-sm">0 Equipos</span>
                        </header>

                        <div id="teamsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            {{-- Se generan con JS --}}
                        </div>

                        <div class="flex gap-3 pt-2 border-t border-gray-50 dark:border-gray-700">
                            <button type="button" onclick="addTeamInput()" class="flex-1 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 rounded-xl border border-indigo-200 dark:border-indigo-800 font-bold text-sm transition-colors flex justify-center items-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Añadir Equipo
                            </button>
                            <button type="button" onclick="addTeamInput(true)" class="px-6 py-3 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-xl border border-gray-300 dark:border-gray-600 font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                + Bye
                            </button>
                        </div>
                    </section>
                </div>

            </form>
        </main>
    </div>

    <script>
        // Sincronizar Cancha
        window.addEventListener('cancha-changed', event => {
            Livewire.dispatch('updateCancha', { id: event.detail.id });
        });

        // Escuchar Selección
        window.addEventListener('tournament-selection-updated', event => {
            const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            const { start_date, duration, first_slot } = data;

            document.getElementById('final_start_date').value = start_date;
            document.getElementById('final_duration').value = duration;

            const summaryBox = document.getElementById('selectionSummary');
            summaryBox.classList.remove('hidden');
            summaryBox.classList.add('block', 'animate-in', 'fade-in', 'slide-in-from-bottom-2');

            document.getElementById('summaryDuration').innerText = duration;
            document.getElementById('summaryTime').innerText = first_slot; 
            
            const dateObj = new Date(start_date);
            document.getElementById('summaryDate').innerText = dateObj.toLocaleDateString();

            enableSubmitBtn();
        });

        window.addEventListener('tournament-selection-cleared', event => {
            document.getElementById('selectionSummary').classList.add('hidden');
            disableSubmitBtn();
        });

        function enableSubmitBtn() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            // Estilo activado: Morado vibrante
            btn.className = "w-full py-4 px-6 bg-indigo-600 text-white font-black text-lg rounded-2xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-3 group shadow-xl shadow-indigo-200 hover:shadow-2xl hover:-translate-y-1 transform border border-transparent";
            btn.innerHTML = `
                <span>CREAR TORNEO</span>
                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            `;
        }

        function disableSubmitBtn() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            // Estilo desactivado: Gris
            btn.className = "w-full py-4 px-6 bg-gray-200 text-gray-400 font-black text-lg rounded-2xl cursor-not-allowed transition-all flex items-center justify-center gap-3 group shadow-sm border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-600";
            btn.innerHTML = "<span>Selecciona horario</span>";
        }

        // Equipos
        document.addEventListener('DOMContentLoaded', () => {
            for(let i=0; i<4; i++) addTeamInput(); 
        });

        function addTeamInput(isBye = false) {
            const container = document.getElementById('teamsGrid');
            const wrapper = document.createElement('div');
            wrapper.className = "relative group animate-in zoom-in-95 duration-200";
            
            const inputClass = "w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-shadow focus:shadow-md";
            const val = isBye ? 'BYE' : '';
            const readOnly = isBye ? 'readonly' : '';
            const style = isBye ? 'bg-gray-50 text-gray-400 italic border-dashed border-gray-300' : 'bg-white text-gray-800 border-gray-300';

            wrapper.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mr-2">
                        ${container.children.length + 1}
                    </div>
                    <input type="text" name="teams[]" value="${val}" ${readOnly} placeholder="Nombre del Equipo" required class="${inputClass} ${style}">
                    <button type="button" onclick="this.closest('.relative').remove(); updateTeamCount();" class="absolute right-2 p-1.5 text-gray-300 hover:text-red-500 transition-colors rounded-md hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
            container.appendChild(wrapper);
            updateTeamCount();
        }

        function updateTeamCount() {
            const count = document.getElementById('teamsGrid').children.length;
            document.getElementById('teamCountBadge').innerText = `${count} Equipos`;
            Array.from(document.getElementById('teamsGrid').children).forEach((el, index) => {
                el.querySelector('.w-8').innerText = index + 1;
            });
        }
    </script>
</x-app-layout>