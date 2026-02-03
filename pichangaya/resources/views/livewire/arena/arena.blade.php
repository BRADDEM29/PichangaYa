<main class="fixed inset-0 z-50 bg-gray-900 text-white overflow-y-auto custom-scrollbar">
    
    {{-- Navegación --}}
    <nav class="relative z-50 bg-gray-900 border-b border-gray-800">
        <livewire:navigation-menu />
    </nav>

    {{-- HERO SECTION --}}
    <header class="relative bg-gray-800 border-b border-gray-700 pb-10 pt-6">
        <section class="max-w-7xl mx-auto px-4 relative z-10">
            
            <h1 class="text-3xl md:text-4xl font-black text-center mb-2 uppercase italic tracking-tighter">
                <span class="text-blue-500">Ranked</span> Matchmaking
            </h1>

            {{-- 🟢 LÓGICA MAESTRA DE VISUALIZACIÓN --}}
            @if($activeLobbyId)
                
                {{-- CASO A: YA TIENES PARTIDA (El buscador está OCULTO) --}}
                <article class="max-w-2xl mx-auto mt-10">
                    <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-3xl p-1 border-2 border-green-500 shadow-[0_0_60px_rgba(34,197,94,0.3)] animate-pulse-slow">
                        <div class="bg-gray-900 rounded-[22px] p-8 text-center">
                            
                            <figure class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-green-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </figure>

                            <h2 class="text-3xl font-black text-white mb-2 uppercase italic">¡Partida en Curso!</h2>
                            <p class="text-gray-400 mb-8 text-lg">
                                Actualmente estás dentro del <strong class="text-green-400">Lobby #{{ $activeLobbyId }}</strong>.
                                <br>No puedes buscar otra partida mientras tengas una activa.
                            </p>

                            <nav class="flex flex-col gap-4 sm:flex-row justify-center">
                                <a href="{{ route('lobby.show', $activeLobbyId) }}" 
                                   class="px-8 py-4 bg-green-600 hover:bg-green-500 text-white font-black rounded-xl shadow-lg hover:shadow-green-500/50 transform hover:scale-105 transition flex items-center justify-center gap-3 uppercase tracking-wide">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Volver a la Sala
                                </a>

                                <button wire:click="cancelSearch" 
                                        wire:confirm="¿Seguro que quieres abandonar tu partida actual?"
                                        class="px-8 py-4 bg-gray-800 border border-red-900/50 text-red-400 hover:bg-red-900/20 hover:text-red-200 font-bold rounded-xl transition flex items-center justify-center gap-2 uppercase text-sm">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Abandonar Partida
                                </button>
                            </nav>

                        </div>
                    </div>
                </article>

            @else

                {{-- CASO B: ESTÁS LIBRE (Muestra el Buscador) --}}
                <article class="bg-gray-900/90 backdrop-blur rounded-2xl p-6 border border-gray-700 shadow-2xl max-w-4xl mx-auto mt-6 relative z-20">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Deporte</label>
                            <select wire:model.live="sport_id" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                                @foreach($sports as $sport)
                                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Distrito</label>
                            <select wire:model.live="district_id" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Fecha</label>
                            <input type="date" wire:model.live="selectedDate" class="w-full bg-gray-800 border-gray-600 text-white rounded-lg font-bold focus:ring-blue-500 focus:border-blue-500 h-12">
                        </div>

                        <button wire:click="startSearch" class="h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black rounded-lg shadow-lg uppercase tracking-wide transition transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            BUSCAR PARTIDA
                        </button>
                    </div>
                </article>

            @endif
        </section>
    </header>

    {{-- SECCIÓN INFERIOR: LOBBYS PÚBLICOS --}}
    <section class="max-w-7xl mx-auto px-4 py-8 relative z-10">
        <header class="font-bold text-gray-400 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <h3 class="uppercase tracking-widest text-sm">Lobbys en Directo</h3>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($publicLobbies as $lobby)
                <article class="bg-gray-800 rounded-lg p-4 border border-gray-700 hover:border-blue-500 transition group relative">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold bg-blue-900 text-blue-200 px-2 py-0.5 rounded uppercase">{{ $lobby->sport->name }}</span>
                            <div class="font-bold text-lg text-white mt-1">Sala #{{ $lobby->id }}</div>
                            <div class="text-xs text-gray-500">{{ $lobby->district->name }}</div>
                        </div>
                        
                        {{-- 🟢 AQUÍ ESTABA EL ERROR: Cambiado de /14 a dinámico --}}
                        <div class="text-right">
                            <div class="font-black text-2xl text-gray-200">
                                {{ $lobby->slots_count }}/{{ $lobby->max_slots }}
                            </div>
                            <div class="text-[10px] text-gray-500">JUGADORES</div>
                        </div>
                    </div>
                    
                    @if($activeLobbyId === $lobby->id)
                        <div class="absolute inset-0 border-2 border-green-500 rounded-lg pointer-events-none"></div>
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">TU SALA</span>
                        </div>
                    @endif
                    
                    <a href="{{ route('lobby.show', $lobby->id) }}" class="absolute inset-0 z-10"></a>
                </article>
            @endforeach
        </div>
    </section>

</main>