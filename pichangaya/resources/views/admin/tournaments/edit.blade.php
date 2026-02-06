<x-app-layout>
    <x-slot name="header">
        {{-- HEADER: Título y Navegación con Estilo Premium --}}
        <header class="flex items-center justify-between">
            <h1 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-indigo-600 dark:from-white dark:to-indigo-400 leading-tight flex items-center gap-3">
                <span class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400 shadow-sm border border-indigo-100 dark:border-indigo-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                {{ __('Modificar Torneo') }}
            </h1>
            
            <nav>
                <a href="{{ route('admin.tournaments.index') }}" class="group flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Cancelar
                </a>
            </nav>
        </header>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-[#0f172a] min-h-screen font-sans transition-colors duration-300">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.tournaments.update', $tournament->id) }}" method="POST" id="tournamentForm" class="flex flex-col lg:flex-row gap-8">
                @csrf
                @method('PUT')
                
                {{-- 🟢 COLUMNA IZQUIERDA: RESUMEN Y DATOS --}}
                <aside class="contents lg:flex lg:flex-col lg:w-1/3 gap-6 h-fit sticky top-8">
                    
                    {{-- TARJETA DE RESUMEN (Estilo Reservas) --}}
                    <article class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-2xl border border-white/10 overflow-hidden text-white relative group">
                        
                        {{-- Decoración de fondo --}}
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-700"></div>
                        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-black/20 rounded-full blur-2xl"></div>

                        <div class="relative z-10 p-6 sm:p-8">
                            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-200 mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                Editando:
                            </h2>
                            <h3 class="text-2xl font-black mb-6 leading-tight">{{ $tournament->name }}</h3>

                            {{-- Bloque de Tiempo Dinámico --}}
                            <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30 shadow-inner">
                                <div class="flex justify-between items-end mb-2">
                                    <label class="text-xs text-indigo-100 font-bold uppercase">Nueva Fecha</label>
                                    <span class="text-xs text-indigo-100 font-bold uppercase">Duración</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-left">
                                        <span class="block text-2xl font-bold tracking-tight" id="summaryTime">
                                            {{ \Carbon\Carbon::parse($tournament->start_date)->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-indigo-100 opacity-90 block" id="summaryDate">
                                            {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-baseline gap-1">
                                            {{-- Calculamos duración inicial --}}
                                            @php
                                                $duracion = 3; // Default
                                                try {
                                                    // Buscamos la reserva original para saber su duración real
                                                    $oldReserva = \App\Models\Reserva::where('cancha_id', $tournament->cancha_id)
                                                        ->where('start_time', $tournament->getOriginal('start_date'))
                                                        ->first();
                                                    if ($oldReserva) {
                                                        $duracion = $oldReserva->end_time->diffInHours($oldReserva->start_time);
                                                    }
                                                } catch(\Exception $e) {}
                                            @endphp
                                            <span class="text-4xl font-black" id="summaryDuration">{{ $duracion }}</span>
                                            <span class="text-sm font-medium opacity-80">Hrs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Inputs Ocultos que se envían al backend --}}
                            <input type="hidden" name="start_date" id="final_start_date" value="{{ $tournament->start_date }}">
                            <input type="hidden" name="duration" id="final_duration" value="{{ $duracion }}">
                        </div>

                        {{-- Botón Guardar Integrado --}}
                        <div class="p-4 bg-black/20 backdrop-blur-sm border-t border-white/10">
                            <button type="submit" id="submitBtn" class="w-full py-3.5 px-6 bg-white text-indigo-900 font-black text-sm uppercase tracking-widest rounded-xl hover:bg-indigo-50 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                                <span>Guardar Cambios</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                        </div>
                    </article>

                    {{-- Configuración Básica --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Datos Generales</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $tournament->name) }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-semibold shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Sede</label>
                                <select name="cancha_id" onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm cursor-pointer">
                                    @foreach($canchas as $cancha)
                                        <option value="{{ $cancha->id }}" {{ $tournament->cancha_id == $cancha->id ? 'selected' : '' }}>
                                            {{ $cancha->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </section>
                </aside>

                {{-- 🟢 COLUMNA DERECHA: CALENDARIO Y EQUIPOS --}}
                <div class="order-2 lg:order-none lg:w-2/3 space-y-8">
                    
                    {{-- 1. CALENDARIO (Glassmorphism Light) --}}
                    <section class="bg-white/80 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/20">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-widest">Seleccionar Nuevo Horario</h3>
                            </div>
                            
                            {{-- Aviso sutil --}}
                            <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-medium text-orange-500 bg-orange-50 dark:bg-orange-900/20 px-2 py-1 rounded-full border border-orange-100 dark:border-orange-800">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Arrastra o selecciona bloques
                            </span>
                        </header>

                        <div class="p-6 relative">
                            {{-- Estilos para limpiar el Livewire --}}
                            <style>.livewire-cancha-form .border-t.border-dashed { display: none !important; }</style>
                            
                            <div class="livewire-cancha-form">
                                @livewire('cancha-reserva-form', [
                                    'cancha' => $canchas->find($tournament->cancha_id), 
                                    'isTournamentMode' => true,
                                    // 🟢 NUEVO: Le pasamos el ID para que NO lo pinte azul (bloqueado)
                                    'ignoringReservaId' => $reserva?->id 
                                ])
                            </div>
                        </div>
                    </section>

                    {{-- 2. EQUIPOS (Diseño Limpio) --}}
                    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <header class="flex justify-between items-center mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Equipos
                            </h3>
                            <span id="teamCountBadge" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-lg text-xs font-bold shadow-sm">0 Equipos</span>
                        </header>

                        <div id="teamsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            {{-- Inyectado por JS --}}
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-gray-50 dark:border-gray-700">
                            <button type="button" onclick="addTeamInput()" class="flex-1 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 rounded-xl border border-indigo-200 dark:border-indigo-800 font-bold text-sm transition-all flex justify-center items-center gap-2">
                                + Añadir Equipo
                            </button>
                            <button type="button" onclick="addTeamInput(true)" class="px-6 py-3 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl border border-gray-300 dark:border-gray-600 font-bold text-sm transition-all">
                                + Bye
                            </button>
                        </div>
                    </section>
                </div>
            </form>
        </main>
    </div>

    {{-- SCRIPTS PARA SINCRONIZACIÓN --}}
    <script>
        // 1. Sincronizar Select de Cancha con Livewire
        window.addEventListener('cancha-changed', event => { 
            Livewire.dispatch('updateCancha', { id: event.detail.id }); 
        });

        // 2. Escuchar cambios en el Calendario (Livewire -> JS -> Inputs)
        // Esto permite cambiar duración y hora
        window.addEventListener('tournament-selection-updated', event => {
            const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            
            // Actualizar inputs ocultos
            document.getElementById('final_start_date').value = data.start_date;
            document.getElementById('final_duration').value = data.duration;
            
            // Actualizar Tarjeta Resumen Visual
            document.getElementById('summaryDuration').innerText = data.duration;
            document.getElementById('summaryTime').innerText = data.first_slot;
            
            // Formatear fecha bonita
            const dateObj = new Date(data.start_date);
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            document.getElementById('summaryDate').innerText = dateObj.toLocaleDateString('es-ES', options);
            
            // Efecto visual de actualización
            const summaryCard = document.getElementById('selectionSummary');
            summaryCard.classList.add('ring-2', 'ring-white');
            setTimeout(() => summaryCard.classList.remove('ring-2', 'ring-white'), 300);
        });

        // 3. Cargar Equipos (CORREGIDO PARA 'team_name')
        document.addEventListener('DOMContentLoaded', () => {
            const existingTeams = @json($tournament->teams);
            
            if(existingTeams && existingTeams.length > 0) {
                existingTeams.forEach(team => {
                    // Usamos team_name que es como se llama en tu BD
                    const name = team.team_name || team.name || '';
                    const isBye = name.toUpperCase() === 'BYE';
                    addTeamInput(isBye, name);
                });
            } else {
                for(let i=0; i<4; i++) addTeamInput(); 
            }
        });

        function addTeamInput(isBye = false, value = '') {
            const container = document.getElementById('teamsGrid');
            const wrapper = document.createElement('div');
            wrapper.className = "relative group animate-in zoom-in-95 duration-200";
            
            const inputClass = "w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-shadow focus:shadow-md";
            const val = value ? value : (isBye ? 'BYE' : '');
            const readOnly = isBye ? 'readonly' : '';
            const style = isBye ? 'bg-gray-50 text-gray-400 italic border-dashed border-gray-300' : 'bg-white text-gray-800 border-gray-300 dark:text-white dark:bg-gray-800';

            wrapper.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mr-2 count-badge">
                        ${container.children.length + 1}
                    </div>
                    <input type="text" name="teams[]" value="${val}" ${readOnly} placeholder="Nombre del Equipo" required class="${inputClass} ${style}">
                    <button type="button" onclick="removeTeam(this)" class="absolute right-2 p-1.5 text-gray-300 hover:text-red-500 rounded-md transition-colors">
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