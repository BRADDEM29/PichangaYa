<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h2 class="font-black text-2xl text-gray-800 dark:text-white leading-tight">
                    Crear Nuevo Torneo
                </h2>
            </div>
        </header>
    </x-slot>

    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <form action="{{ route('admin.tournaments.store') }}" method="POST" id="tournamentForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                {{-- ASIDE: CONFIGURACIÓN --}}
                <aside class="lg:col-span-1 space-y-6">
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Detalles del Evento
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre del Torneo</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-purple-500 focus:border-purple-500 font-medium" placeholder="Ej: Copa Verano 2024" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Sede (Cancha)</label>
                                <select name="cancha_id" id="cancha_select" 
                                    onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))"
                                    class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-purple-500 focus:border-purple-500 font-medium" required>
                                    @foreach($canchas as $cancha)
                                        <option value="{{ $cancha->id }}" {{ old('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                            {{ $cancha->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- RESUMEN FLOTANTE DE SELECCIÓN --}}
                    <div id="selectionSummary" class="hidden bg-purple-600 rounded-2xl shadow-xl p-6 text-white transform transition-all duration-500 hover:scale-[1.02]">
                        <h4 class="text-purple-200 text-[10px] font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
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
                                <span class="text-xs text-purple-200 font-medium opacity-80" id="summaryDate">--/--/--</span>
                            </div>
                        </div>
                        
                        <input type="hidden" name="start_date" id="final_start_date">
                        <input type="hidden" name="duration" id="final_duration">
                    </div>

                    <button type="submit" id="submitBtn" disabled class="w-full py-4 bg-gray-200 text-gray-400 font-bold rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2 group shadow-sm border border-gray-300">
                        <span>Selecciona horario</span>
                    </button>
                </aside>

                {{-- SECCIÓN CENTRAL --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- COMPONENTE LIVEWIRE --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <header class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Disponibilidad
                            </h3>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 uppercase tracking-wide border border-purple-200">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                                </span>
                                Modo Torneo
                            </span>
                        </header>
                        
                        <div class="p-6 bg-white dark:bg-gray-900/50">
                            {{-- Ocultar botón interno de usuario --}}
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

                    {{-- EQUIPOS --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Participantes
                            </h3>
                            <span id="teamCountBadge" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200">0 Equipos</span>
                        </div>

                        <div id="teamsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            {{-- Se generan con JS --}}
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="addTeamInput()" class="flex-1 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl border border-indigo-200 font-bold text-sm transition-colors flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Añadir Equipo
                            </button>
                            <button type="button" onclick="addTeamInput(true)" class="px-6 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl border border-gray-200 font-bold text-sm transition-colors flex items-center gap-2">
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
            btn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed', 'border-gray-300');
            btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'shadow-lg', 'shadow-indigo-200', 'border-transparent');
            btn.querySelector('span').innerText = 'Crear Torneo';
        }

        function disableSubmitBtn() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed', 'border-gray-300');
            btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'shadow-lg', 'shadow-indigo-200', 'border-transparent');
            btn.querySelector('span').innerText = 'Selecciona horario';
        }

        // Equipos
        document.addEventListener('DOMContentLoaded', () => {
            for(let i=0; i<4; i++) addTeamInput(); 
        });

        function addTeamInput(isBye = false) {
            const container = document.getElementById('teamsGrid');
            const wrapper = document.createElement('div');
            wrapper.className = "relative group animate-in zoom-in-95 duration-200";
            
            const inputClass = "w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-shadow focus:shadow-md";
            const val = isBye ? 'BYE' : '';
            const readOnly = isBye ? 'readonly' : '';
            const style = isBye ? 'bg-gray-50 text-gray-400 italic border-dashed' : 'bg-white text-gray-800';

            wrapper.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mr-2">
                        ${container.children.length + 1}
                    </div>
                    <input type="text" name="teams[]" value="${val}" ${readOnly} placeholder="Nombre del Equipo" required class="${inputClass} ${style}">
                    <button type="button" onclick="this.closest('.relative').remove(); updateTeamCount();" class="absolute right-2 p-1.5 text-gray-300 hover:text-red-500 transition-colors rounded-md hover:bg-red-50">
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