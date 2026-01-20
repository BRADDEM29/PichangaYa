<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="text-indigo-600">🏆</span> Crear Torneo Versátil
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.tournaments.store') }}" method="POST" id="tournamentForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- COLUMNA IZQUIERDA: Configuración --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- Tarjeta: Datos Básicos --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">⚙️ Configuración</h3>
                            
                            {{-- Nombre --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">Nombre del Evento</label>
                                <input type="text" name="name" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500" placeholder="Ej: Copa Relámpago" required>
                            </div>

                            {{-- Selección de Cancha (Modelo Cancha) --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">📍 Sede (Cancha)</label>
                                <select name="cancha_id" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500" required>
                                    <option value="" disabled selected>-- Selecciona una cancha --</option>
                                    @foreach($canchas as $cancha)
                                        <option value="{{ $cancha->id }}">
                                            {{ $cancha->name }} 
                                            @if($cancha->district) - {{ $cancha->district->name }} @endif
                                            (S/ {{ number_format($cancha->price_per_hour, 2) }}/h)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fecha y Hora --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">📅 Inicio</label>
                                <input type="datetime-local" name="start_date" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500" required>
                            </div>
                        </div>

                        {{-- Botón Submit --}}
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black text-lg rounded-xl shadow-xl transform transition hover:scale-[1.02]">
                            GENERAR BRACKET
                        </button>
                    </div>

                    {{-- COLUMNA DERECHA: Equipos Dinámicos --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 h-full">
                            <div class="flex justify-between items-center mb-6 border-b pb-4">
                                <div>
                                    <h3 class="font-bold text-xl text-gray-800 dark:text-white">Participantes</h3>
                                    <p class="text-xs text-gray-500">Agrega 4, 5, 7, 9... los equipos que desees. El sistema calculará los pases directos (Byes).</p>
                                </div>
                                <div class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-black text-xl" id="teamCounter">
                                    0
                                </div>
                            </div>

                            {{-- Contenedor de Inputs --}}
                            <div id="teamsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                {{-- Aquí se insertan los inputs con JS --}}
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="mt-6 flex gap-3">
                                <button type="button" onclick="addTeamInput()" class="flex-1 py-3 border-2 border-dashed border-gray-300 text-gray-500 font-bold rounded-lg hover:border-indigo-500 hover:text-indigo-500 hover:bg-indigo-50 transition flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                    Agregar Equipo
                                </button>
                                <button type="button" onclick="addTeamInput(true)" class="px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition" title="Agregar espacio vacío (Bye)">
                                    + Bye
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para manejo dinámico de equipos --}}
    <script>
        // Inicializamos con 4 equipos por defecto
        document.addEventListener('DOMContentLoaded', () => {
            for(let i=0; i<4; i++) addTeamInput();
        });

        function addTeamInput(isBye = false) {
            const container = document.getElementById('teamsContainer');
            const index = container.children.length + 1;
            
            const div = document.createElement('div');
            div.className = "flex items-center gap-2 animate-fade-in-up";
            
            const inputHtml = isBye 
                ? `<input type="text" name="teams[]" value="BYE" readonly class="flex-1 bg-gray-100 text-gray-400 italic border-gray-200 rounded-lg cursor-not-allowed text-sm" />`
                : `<div class="relative w-full">
                     <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 font-bold text-xs">${index}</span>
                     <input type="text" name="teams[]" class="w-full pl-8 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nombre del Equipo" required>
                   </div>`;

            div.innerHTML = `
                ${inputHtml}
                <button type="button" onclick="this.parentElement.remove(); updateCounter();" class="text-red-400 hover:text-red-600 transition p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </button>
            `;
            
            container.appendChild(div);
            updateCounter();
            
            // Auto scroll al final
            container.scrollTop = container.scrollHeight;
        }

        function updateCounter() {
            const count = document.getElementById('teamsContainer').children.length;
            document.getElementById('teamCounter').innerText = count;
        }
    </script>
    
    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.3s ease-out forwards; }
        /* Scrollbar personalizado */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>
</x-app-layout>