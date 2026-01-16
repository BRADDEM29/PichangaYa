<div class="fixed inset-0 z-50 bg-gray-900 text-white overflow-y-auto">
    
    {{-- Navegación --}}
    <div class="relative z-50 bg-gray-900 border-b border-gray-800">
        <livewire:navigation-menu />
    </div>

    {{-- HERO SECTION --}}
    <div class="relative bg-gray-800 border-b border-gray-700 pb-10 pt-6">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            
            <h1 class="text-3xl md:text-4xl font-black text-center mb-2 uppercase italic tracking-tighter">
                <span class="text-blue-500">Ranked</span> Matchmaking
            </h1>

            {{-- 🟢 LÓGICA MAESTRA DE VISUALIZACIÓN --}}
            @if($activeLobbyId)
                
                {{-- CASO A: YA TIENES PARTIDA (El buscador está OCULTO) --}}
                <div class="max-w-2xl mx-auto mt-10">
                    <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-3xl p-1 border-2 border-green-500 shadow-[0_0_60px_rgba(34,197,94,0.3)] animate-pulse-slow">
                        <div class="bg-gray-900 rounded-[22px] p-8 text-center">
                            
                            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-4xl animate-bounce">🎮</span>
                            </div>

                            <h2 class="text-3xl font-black text-white mb-2 uppercase italic">¡Partida en Curso!</h2>
                            <p class="text-gray-400 mb-8 text-lg">
                                Actualmente estás dentro del <strong class="text-green-400">Lobby #{{ $activeLobbyId }}</strong>.
                                <br>No puedes buscar otra partida mientras tengas una activa.
                            </p>

                            <div class="flex flex-col gap-4 sm:flex-row justify-center">
                                {{-- BOTÓN VERDE: Te lleva de vuelta a tu sala --}}
                                <a href="{{ route('lobby.show', $activeLobbyId) }}" 
                                   class="px-8 py-4 bg-green-600 hover:bg-green-500 text-white font-black rounded-xl shadow-lg hover:shadow-green-500/50 transform hover:scale-105 transition flex items-center justify-center gap-3 uppercase tracking-wide">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Volver a la Sala
                                </a>

                                {{-- BOTÓN ROJO: Solo este botón te permite volver a buscar --}}
                                <button wire:click="cancelSearch" 
                                        wire:confirm="¿Seguro que quieres abandonar tu partida actual?"
                                        class="px-8 py-4 bg-gray-800 border border-red-900/50 text-red-400 hover:bg-red-900/20 hover:text-red-200 font-bold rounded-xl transition flex items-center justify-center gap-2 uppercase text-sm">
                                    Abandonar Partida
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @else

                {{-- CASO B: ESTÁS LIBRE (Muestra el Buscador) --}}
                <div class="bg-gray-900/90 backdrop-blur rounded-2xl p-6 border border-gray-700 shadow-2xl max-w-4xl mx-auto mt-6 relative z-20">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        
                        {{-- Select Deporte --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Deporte</label>
                            <select wire:model.live="sport_id" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Distrito --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Distrito</label>
                            <select wire:model.live="district_id" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Fecha --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Fecha</label>
                            <input type="date" wire:model.live="selectedDate" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                        </div>

                        {{-- Botón Buscar --}}
                        <button wire:click="startSearch" class="h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black rounded-lg shadow-lg uppercase tracking-wide transition transform active:scale-95 flex items-center justify-center gap-2">
                            <span>🔍</span> BUSCAR PARTIDA
                        </button>
                    </div>
                </div>

            @endif
        </div>
    </div>

    {{-- SECCIÓN INFERIOR: LOBBYS PÚBLICOS (ESPECTADOR) --}}
    <div class="max-w-7xl mx-auto px-4 py-8 relative z-10">
        <h3 class="font-bold text-gray-400 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            LOBBYS EN DIRECTO
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($publicLobbies as $lobby)
                <div class="bg-gray-800 rounded-lg p-4 border border-gray-700 hover:border-blue-500 transition group relative">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold bg-blue-900 text-blue-200 px-2 py-0.5 rounded uppercase">{{ $lobby->sport->name }}</span>
                            <div class="font-bold text-lg text-white mt-1">Sala #{{ $lobby->id }}</div>
                            <div class="text-xs text-gray-500">{{ $lobby->district->name }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-2xl text-gray-200">{{ $lobby->slots_count }}/14</div>
                            <div class="text-[10px] text-gray-500">JUGADORES</div>
                        </div>
                    </div>
                    
                    {{-- Si por alguna razón extraña la variable coincide visualmente --}}
                    @if($activeLobbyId === $lobby->id)
                        <div class="absolute inset-0 border-2 border-green-500 rounded-lg pointer-events-none"></div>
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">TU SALA</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

</div>