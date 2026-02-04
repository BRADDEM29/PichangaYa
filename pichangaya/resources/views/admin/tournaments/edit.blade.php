<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-xl border border-blue-200 dark:border-blue-800">
                    {{-- Icono de Lápiz --}}
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h1 class="font-black text-2xl text-gray-800 dark:text-white leading-tight">
                    Editar Torneo: {{ $tournament->name }}
                </h1>
            </div>
        </header>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <form action="{{ route('admin.tournaments.update', $tournament->id) }}" method="POST" id="tournamentForm" class="flex flex-col lg:flex-row gap-8">
                @csrf
                @method('PUT') {{-- IMPORTANTE PARA EDITAR --}}
                
                <aside class="contents lg:flex lg:flex-col lg:w-1/3 gap-6 h-fit sticky top-8">
                    
                    {{-- 1. INFO --}}
                    <section class="order-1 lg:order-none bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <header class="mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">Detalles</h3>
                        </header>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre del Torneo</label>
                                <input type="text" name="name" value="{{ old('name', $tournament->name) }}" class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 font-medium shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Sede (Cancha)</label>
                                <div class="relative">
                                    <select name="cancha_id" id="cancha_select" 
                                            onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))"
                                            class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 font-medium shadow-sm appearance-none cursor-pointer" required>
                                        @foreach($canchas as $cancha)
                                            <option value="{{ $cancha->id }}" {{ $tournament->cancha_id == $cancha->id ? 'selected' : '' }}>
                                                {{ $cancha->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 3. ACCIONES Y RESUMEN --}}
                    <div class="order-3 lg:order-none space-y-6">
                        
                        {{-- Tarjeta Resumen (Visible por defecto en Edit) --}}
                        <div id="selectionSummary" class="bg-blue-600 rounded-2xl shadow-lg border border-blue-700 p-6 text-white transform transition-all duration-500 ring-4 ring-blue-50 dark:ring-blue-900/20">
                            <h4 class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-3 pb-2 border-b border-blue-500/30 flex items-center gap-2">
                                Horario Actual / Nuevo
                            </h4>
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-5xl font-black block leading-none tracking-tighter" id="summaryDuration">{{ $tournament->duration ?? 0 }}</span>
                                    <span class="text-sm font-medium text-blue-200">Horas</span>
                                </div>
                                <div class="text-right">
                                    {{-- Lógica simple para mostrar hora --}}
                                    <span class="block text-2xl font-bold tracking-tight" id="summaryTime">
                                        {{ \Carbon\Carbon::parse($tournament->start_date)->format('H:i') }}
                                    </span>
                                    <span class="text-xs text-blue-200 font-medium opacity-80 block mt-1" id="summaryDate">
                                        {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Inputs Hidden con los valores actuales --}}
                            <input type="hidden" name="start_date" id="final_start_date" value="{{ $tournament->start_date }}">
                            <input type="hidden" name="duration" id="final_duration" value="{{ $tournament->duration }}">
                        </div>

                        <button type="submit" id="submitBtn" class="w-full py-4 px-6 bg-blue-600 text-white font-black text-lg rounded-2xl hover:bg-blue-700 transition-all flex items-center justify-center gap-3 group shadow-xl shadow-blue-200 hover:shadow-2xl hover:-translate-y-1 transform border border-transparent">
                            <span>GUARDAR CAMBIOS</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('admin.tournaments.index') }}" class="text-sm text-gray-500 underline hover:text-gray-800">Cancelar</a>
                        </div>
                    </div>
                </aside>

                {{-- 2. CONTENIDO PRINCIPAL --}}
                <div class="order-2 lg:order-none lg:w-2/3 space-y-6">
                    
                    {{-- Tarjeta Livewire (Disponibilidad) --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/20">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Cambiar Horario (Opcional)</h3>
                        </header>
                        <div class="p-6 bg-white dark:bg-gray-900/50">
                            <style>.livewire-cancha-form .border-t.border-dashed { display: none !important; }</style>
                            <div class="livewire-cancha-form">
                                @livewire('cancha-reserva-form', [
                                    'cancha' => $canchas->find($tournament->cancha_id), 
                                    'isTournamentMode' => true
                                ])
                            </div>
                        </div>
                    </section>

                    {{-- Tarjeta Equipos --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <header class="flex justify-between items-center mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Participantes</h3>
                            <span id="teamCountBadge" class="bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold">0 Equipos</span>
                        </header>

                        <div id="teamsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            {{-- Se generan con JS --}}
                        </div>

                        <div class="flex gap-3 pt-2 border-t border-gray-50 dark:border-gray-700">
                            <button type="button" onclick="addTeamInput()" class="flex-1 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl border border-blue-200 font-bold text-sm transition-colors flex justify-center items-center gap-2">
                                Añadir Equipo
                            </button>
                            <button type="button" onclick="addTeamInput(true)" class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-600 rounded-xl border border-gray-300 font-bold text-sm">
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
        window.addEventListener('cancha-changed', event => { Livewire.dispatch('updateCancha', { id: event.detail.id }); });

        // Escuchar Selección de Livewire (Si el usuario decide cambiar el horario)
        window.addEventListener('tournament-selection-updated', event => {
            const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            document.getElementById('final_start_date').value = data.start_date;
            document.getElementById('final_duration').value = data.duration;
            
            // Actualizar visualmente la caja resumen
            document.getElementById('summaryDuration').innerText = data.duration;
            document.getElementById('summaryTime').innerText = data.first_slot;
            document.getElementById('summaryDate').innerText = new Date(data.start_date).toLocaleDateString();
        });

        // Cargar Equipos Existentes al iniciar
        document.addEventListener('DOMContentLoaded', () => {
            const existingTeams = @json($tournament->teams);
            
            if(existingTeams.length > 0) {
                existingTeams.forEach(team => {
                    const isBye = team.name.toUpperCase() === 'BYE';
                    addTeamInput(isBye, team.name);
                });
            } else {
                for(let i=0; i<4; i++) addTeamInput(); 
            }
        });

        function addTeamInput(isBye = false, value = '') {
            const container = document.getElementById('teamsGrid');
            const wrapper = document.createElement('div');
            wrapper.className = "relative group animate-in zoom-in-95 duration-200";
            
            const inputClass = "w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 shadow-sm";
            const val = value ? value : (isBye ? 'BYE' : '');
            const readOnly = isBye ? 'readonly' : '';
            const style = isBye ? 'bg-gray-50 text-gray-400 italic border-dashed border-gray-300' : 'bg-white text-gray-800 border-gray-300';

            wrapper.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center font-black text-xs mr-2 count-badge">
                        ${container.children.length + 1}
                    </div>
                    <input type="text" name="teams[]" value="${val}" ${readOnly} placeholder="Nombre del Equipo" required class="${inputClass} ${style}">
                    <button type="button" onclick="removeTeam(this)" class="absolute right-2 p-1.5 text-gray-300 hover:text-red-500 rounded-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
            container.appendChild(wrapper);
            updateTeamCount();
        }

        function removeTeam(btn) {
            btn.closest('.relative').remove(); 
            updateTeamCount();
        }

        function updateTeamCount() {
            const container = document.getElementById('teamsGrid');
            document.getElementById('teamCountBadge').innerText = `${container.children.length} Equipos`;
            Array.from(container.children).forEach((el, index) => {
                el.querySelector('.count-badge').innerText = index + 1;
            });
        }
    </script>
</x-app-layout>