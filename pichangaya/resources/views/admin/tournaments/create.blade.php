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
            
            {{-- 🔴 BLOQUE DE ERRORES: Esto te dirá por qué no se crea --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-red-800">
                                Hay errores en tu formulario
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.tournaments.store') }}" method="POST" id="tournamentForm" class="space-y-8">
                @csrf
                {{-- Input oculto CRÍTICO --}}
                <input type="hidden" name="start_date" id="final_start_date" value="{{ old('start_date') }}">
                <input type="hidden" name="duration" id="final_duration" value="1">
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
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 py-3" placeholder="Ej: Copa de Verano 2024" required>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Sede del Evento</label>
                                <div class="relative">
                                    <select name="cancha_id" id="cancha_select" 
                                        onchange="window.dispatchEvent(new CustomEvent('cancha-changed', { detail: { id: this.value } }))"
                                        class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 py-3 appearance-none shadow-sm" required>
                                        <option value="" disabled selected>Selecciona una cancha comercial...</option>
                                        @foreach($canchas as $cancha)
                                            <option value="{{ $cancha->id }}" {{ old('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                                {{ $cancha->name }} — S/ {{ $cancha->price_per_hour }}/h
                                            </option>
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

                {{-- PASO 2: HORARIO --}}
                <section class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden border-t-4 border-t-indigo-600">
                    <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-indigo-50/30 dark:bg-indigo-900/10">
                        <h3 class="flex items-center gap-2 font-bold text-gray-800 dark:text-white">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px]">2</span>
                            Disponibilidad en Tiempo Real
                        </h3>
                    </header>
                    <div class="p-6">
                        {{-- Mensaje de validación específico para el horario --}}
                        <div id="date_error_msg" class="hidden mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">¡Atención!</strong>
                            <span class="block sm:inline">Debes seleccionar una fecha y hora en el calendario de abajo.</span>
                        </div>

                        <div class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-600 p-2 bg-white dark:bg-gray-800">
                            @livewire('cancha-reserva-form', [
                                'cancha' => $canchas->first(), 
                                'isTournamentMode' => true
                            ])
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
        // 1. ESCUCHAR EL SELECT DE SEDE
        window.addEventListener('cancha-changed', event => {
            const canchaId = event.detail.id;
            Livewire.dispatch('updateCancha', { id: canchaId });
        });

        // 2. CAPTURAR LA SELECCIÓN DEL CALENDARIO
        window.addEventListener('time-selected', event => {
            const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            // 🟢 Ahora extraemos también la duración
            const { date, time, duration } = detail;
            
            if (date && time) {
                document.getElementById('final_start_date').value = `${date} ${time}`;
                
                // 🟢 Asignamos la duración al input oculto
                if(duration) {
                    document.getElementById('final_duration').value = duration;
                }

                document.getElementById('date_error_msg').classList.add('hidden');
                console.log("Horario fijado:", date, time, "Duración:", duration + "h");
            }
        });

        // 3. VALIDACIÓN PRE-ENVÍO (¡IMPORTANTE!)
        document.getElementById('tournamentForm').addEventListener('submit', function(e) {
            const startDate = document.getElementById('final_start_date').value;
            
            if (!startDate) {
                e.preventDefault(); // DETENER EL ENVÍO
                
                // Mostrar mensaje de error visual
                document.getElementById('date_error_msg').classList.remove('hidden');
                
                // Scroll suave hacia el calendario
                document.getElementById('date_error_msg').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                alert('⚠️ FALTAN DATOS: Por favor selecciona un bloque de horario en el calendario (Paso 2).');
            }
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
            
            // Nota: Para Laravel es mejor usar inputs normales que se validen como array
            const content = isBye 
                ? `<input type="text" name="teams[]" value="BYE" readonly class="${inputClass} bg-gray-50 text-gray-400 italic cursor-not-allowed">`
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