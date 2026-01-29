<section class="fixed inset-0 z-50 bg-[#0f172a] text-white overflow-y-auto custom-scrollbar flex flex-col" wire:poll.3s="checkLobbyStatus">
    
    {{-- =============================================================== --}}
    {{-- 🔒 MODAL BLINDADO: Solo aparece si REALMENTE está lleno         --}}
    {{-- =============================================================== --}}
    @if(($lobby->status === 'confirming' || $lobby->status === 'ready_to_play') && $playerCount >= $maxPlayers && $maxPlayers > 0)
        
        <dialog open class="fixed inset-0 z-[60] flex items-center justify-center w-full h-full bg-black/90 backdrop-blur-md p-4 animate-in fade-in">
            <article class="bg-gray-900 border-2 border-green-500 rounded-2xl p-8 max-w-lg w-full text-center shadow-[0_0_50px_rgba(34,197,94,0.3)] relative overflow-hidden">
                
                <figure class="absolute inset-0 bg-green-500/10 animate-pulse pointer-events-none"></figure>

                <header class="relative z-10 space-y-4">
                    <h2 class="text-3xl md:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg">
                        {{ $lobby->status === 'ready_to_play' ? '¡Todos Listos!' : '¡Partida Encontrada!' }}
                    </h2>
                    <p class="text-green-400 font-bold tracking-widest text-sm uppercase animate-bounce">
                        {{ $lobby->status === 'ready_to_play' ? 'Esperando al líder...' : 'Sala Llena • Confirma tu asistencia' }}
                    </p>
                </header>
                
                <footer class="mt-8 relative z-10">
                    
                    {{-- SI YA ESTAMOS LISTOS PARA JUGAR (SOLO TEXTO PARA USUARIO NORMAL) --}}
                    @if($lobby->status === 'ready_to_play')
                        <div class="p-4 bg-green-900/30 rounded-xl border border-green-500/50">
                            <p class="text-sm text-green-200">Todos han confirmado. El capitán está iniciando la reserva...</p>
                        </div>
                    
                    {{-- SI ESTAMOS CONFIRMANDO --}}
                    @else
                        @if($userSlot->confirmed_at)
                            <div class="space-y-4">
                                <button disabled class="w-full py-4 bg-green-600/20 border border-green-500 text-green-400 font-black rounded-xl uppercase tracking-widest cursor-wait flex justify-center items-center gap-2">
                                    <span>🚀 Listo</span>
                                    <span class="text-xs font-normal opacity-70">Esperando al resto...</span>
                                </button>
                                <button wire:click="toggleReady" class="text-xs text-red-400 underline hover:text-red-300">
                                    Cancelar mi asistencia
                                </button>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-4">
                                <button wire:click="exitLobby" wire:confirm="¿Rechazar partida y salir?" class="py-4 bg-gray-800 hover:bg-red-900 border border-gray-600 text-gray-300 hover:text-white font-bold rounded-xl uppercase tracking-widest transition">
                                    Rechazar
                                </button>
                                <button wire:click="toggleReady" class="py-4 bg-green-500 hover:bg-green-400 text-black font-black text-xl rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.5)] transform hover:scale-105 transition uppercase flex justify-center items-center gap-2">
                                    <span>¡Aceptar!</span>
                                </button>
                            </div>
                        @endif
                        <p class="mt-4 text-xs text-gray-500 font-mono">
                            Jugadores listos: <span class="text-white font-bold">{{ $confirmedCount }}</span> / {{ $maxPlayers }}
                        </p>
                    @endif
                </footer>
            </article>
        </dialog>
    @endif

    {{-- NAV SUPERIOR --}}
    <nav class="relative z-40 bg-gray-900 shadow-md">
        <livewire:navigation-menu />
    </nav>

    {{-- HEADER DE SALA --}}
    <header class="bg-gray-800 border-b border-gray-700 py-4 px-4 shadow-lg sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter flex items-center gap-2">
                <span class="text-blue-500">Lobby</span> #{{ $lobby->id }}
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded not-italic font-sans">{{ $lobby->sport->name }}</span>
            </h1>
            
            <aside class="flex items-center gap-6 bg-gray-900/80 px-6 py-2 rounded-full border border-gray-700">
                <div class="text-center">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest">Estado</span>
                    <span class="text-sm font-bold {{ $lobby->status === 'searching' ? 'text-blue-400' : 'text-green-400' }} uppercase">
                        {{ $lobby->status === 'searching' ? 'Buscando...' : 'Lleno' }}
                    </span>
                </div>
                <div class="w-px h-8 bg-gray-700"></div>
                <div class="text-center">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest">Jugadores</span>
                    <span class="text-xl font-mono font-bold text-white">{{ $playerCount }}/{{ $maxPlayers }}</span>
                </div>
            </aside>
        </div>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- COLUMNA IZQUIERDA: EQUIPOS --}}
        <section class="lg:col-span-8 space-y-8" aria-label="Zona de Equipos">
            
            @php
                $slotsPerTeam = max(1, intdiv($maxPlayers, 2));
                $teamA = $lobby->slots->where('team_side', 'A')->values();
                $teamB = $lobby->slots->where('team_side', 'B')->values();
                $isFutbol = str_contains(strtolower($lobby->sport->name), 'futbol') || str_contains(strtolower($lobby->sport->name), 'fútbol');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- EQUIPO A --}}
                <article class="bg-gray-800/50 rounded-2xl border border-gray-700 overflow-hidden flex flex-col min-h-[300px]">
                    <header class="bg-gray-900/80 p-4 border-b border-gray-700 flex justify-between items-center border-l-4 border-l-blue-500">
                        <h3 class="text-sm font-black uppercase tracking-wider text-blue-400">Equipo A</h3>
                        <span class="text-xs bg-gray-800 px-2 py-0.5 rounded text-gray-400 font-mono">{{ $teamA->count() }}/{{ $slotsPerTeam }}</span>
                    </header>
                    <ul class="p-4 space-y-3 flex-1">
                        @for ($i = 0; $i < $slotsPerTeam; $i++)
                            @if(isset($teamA[$i]))
                                <li class="flex items-center justify-between bg-gray-700/50 p-2 rounded-lg border border-gray-600 shadow-sm animate-in fade-in">
                                    <figure class="flex items-center gap-3">
                                        <img src="{{ $teamA[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-500">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-white truncate max-w-[100px]">{{ $teamA[$i]->user->name }}</span>
                                            @if($teamA[$i]->confirmed_at)
                                                <span class="text-[10px] text-green-400 flex items-center gap-1">✅ Listo</span>
                                            @else
                                                <span class="text-[10px] text-yellow-500">⏳ Pendiente</span>
                                            @endif
                                        </div>
                                    </figure>
                                    @if($teamA[$i]->user_id === auth()->id())
                                        <button wire:click="toggleCaptain" class="text-gray-500 hover:text-yellow-400 p-2 transition" title="Pedir Capitanía">
                                            {{ $teamA[$i]->is_captain ? '👑' : '©' }}
                                        </button>
                                    @elseif($teamA[$i]->is_captain)
                                        <span class="text-xl" title="Capitán">👑</span>
                                    @endif
                                </li>
                            @else
                                <li class="h-12 border border-dashed border-gray-700 rounded-lg bg-gray-800/30 flex items-center justify-center cursor-pointer hover:border-blue-500/50 hover:bg-blue-500/5 transition group"
                                    wire:click="moveToTeam('A')">
                                    <span class="text-[10px] font-bold text-gray-500 group-hover:text-blue-400 uppercase tracking-widest">Unirse</span>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>

                {{-- EQUIPO B --}}
                <article class="bg-gray-800/50 rounded-2xl border border-gray-700 overflow-hidden flex flex-col min-h-[300px]">
                    <header class="bg-gray-900/80 p-4 border-b border-gray-700 flex justify-between items-center border-r-4 border-r-red-500">
                        <h3 class="text-sm font-black uppercase tracking-wider text-red-400">Equipo B</h3>
                        <span class="text-xs bg-gray-800 px-2 py-0.5 rounded text-gray-400 font-mono">{{ $teamB->count() }}/{{ $slotsPerTeam }}</span>
                    </header>
                    <ul class="p-4 space-y-3 flex-1">
                        @for ($i = 0; $i < $slotsPerTeam; $i++)
                            @if(isset($teamB[$i]))
                                <li class="flex items-center justify-between bg-gray-700/50 p-2 rounded-lg border border-gray-600 shadow-sm animate-in fade-in">
                                    <figure class="flex items-center gap-3">
                                        <img src="{{ $teamB[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-500">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-white truncate max-w-[100px]">{{ $teamB[$i]->user->name }}</span>
                                            @if($teamB[$i]->confirmed_at)
                                                <span class="text-[10px] text-green-400 flex items-center gap-1">✅ Listo</span>
                                            @else
                                                <span class="text-[10px] text-yellow-500">⏳ Pendiente</span>
                                            @endif
                                        </div>
                                    </figure>
                                    @if($teamB[$i]->user_id === auth()->id())
                                        <button wire:click="toggleCaptain" class="text-gray-500 hover:text-yellow-400 p-2 transition">
                                            {{ $teamB[$i]->is_captain ? '👑' : '©' }}
                                        </button>
                                    @elseif($teamB[$i]->is_captain)
                                        <span class="text-xl" title="Capitán">👑</span>
                                    @endif
                                </li>
                            @else
                                <li class="h-12 border border-dashed border-gray-700 rounded-lg bg-gray-800/30 flex items-center justify-center cursor-pointer hover:border-red-500/50 hover:bg-red-500/5 transition group"
                                    wire:click="moveToTeam('B')">
                                    <span class="text-[10px] font-bold text-gray-500 group-hover:text-red-400 uppercase tracking-widest">Unirse</span>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>
            </div>

            {{-- BOTÓN (+) --}}
            @if(!$isFutbol && $lobby->status === 'searching')
                <div class="flex justify-center mt-4">
                    <button wire:click="increaseCapacity" wire:loading.attr="disabled"
                            class="flex items-center gap-2 px-6 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-full text-xs font-bold uppercase tracking-widest text-gray-300 hover:text-white transition shadow-lg transform hover:scale-105 active:scale-95">
                        <span wire:loading.remove>➕ Agregar Cupos (+2)</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            @endif

            {{-- CARRUSEL --}}
            @if($carouselItems && $carouselItems->count() > 0)
                <section class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-2xl mt-8">
                    <header class="flex items-center gap-3 mb-6">
                        <h3 class="text-xl font-black text-white uppercase tracking-widest flex items-center gap-2">
                            <span class="text-yellow-400 text-2xl">⚡</span> 
                            Canchas Disponibles
                        </h3>
                    </header>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($carouselItems as $cancha)
                            <article class="group relative h-48 bg-gray-900 rounded-xl overflow-hidden border border-gray-700 cursor-pointer hover:border-yellow-400 transition-all shadow-lg hover:shadow-yellow-400/20 transform hover:-translate-y-1">
                                @if($cancha->media->first())
                                    <img src="{{ $cancha->media->first()->getUrl() }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-800 text-gray-500 text-xs">Sin Foto</div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent flex flex-col justify-end p-4">
                                    <h4 class="text-base font-black text-white leading-none mb-1 group-hover:text-yellow-400 transition">{{ $cancha->name }}</h4>
                                    <p class="text-[10px] text-gray-300">{{ $cancha->district->name }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </section>

        {{-- CHAT --}}
        <aside class="lg:col-span-4 flex flex-col gap-6 h-[600px] lg:h-auto">
            <section class="flex-1 bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden flex flex-col shadow-xl">
                <header class="bg-gray-900 p-4 border-b border-gray-700">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Chat de Sala</h3>
                </header>
                <ul class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar bg-gray-800/50">
                    @forelse($lobbyMessages as $msg)
                        <li class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <span class="text-[10px] text-gray-500 mb-1 px-1">{{ $msg->sender->name }}</span>
                            <div class="max-w-[85%] px-3 py-2 rounded-xl text-sm shadow-sm {{ $msg->user_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-700 text-gray-200 rounded-bl-none' }}">
                                {{ $msg->content }}
                            </div>
                        </li>
                    @empty
                        <li class="h-full flex flex-col items-center justify-center opacity-30 text-xs text-gray-500">
                            <span>Di hola... 👋</span>
                        </li>
                    @endforelse
                </ul>
                <footer class="p-3 bg-gray-900 border-t border-gray-700">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <input type="text" wire:model="newMessage" placeholder="Escribe aquí..." class="flex-1 bg-gray-800 border-gray-700 rounded-lg text-sm text-white px-3 py-2 outline-none">
                        <button type="submit" class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition">🚀</button>
                    </form>
                </footer>
            </section>

            <footer class="space-y-3">
                <button wire:click="exitLobby" wire:confirm="¿Abandonar la sala?" 
                        class="w-full py-4 bg-red-900/20 hover:bg-red-600/30 text-red-400 hover:text-red-200 font-bold border border-red-900/50 rounded-xl transition text-xs uppercase tracking-widest">
                    Abandonar Sala
                </button>
            </footer>
        </aside>
    </main>

    {{-- MENÚ DEL LÍDER (FASE FINAL) --}}
    @if($lobby->status === 'ready_to_play' && $userSlot->is_captain)
        <aside class="fixed bottom-0 left-0 w-full bg-black/90 backdrop-blur-xl border-t-4 border-yellow-500 p-6 z-50 animate-in slide-in-from-bottom duration-500">
            <section class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <header class="text-center md:text-left">
                    <h2 class="text-2xl font-black text-yellow-400 uppercase italic leading-none">EQUIPO LISTO</h2>
                    <p class="text-gray-300 text-xs uppercase tracking-widest mt-1">Todos confirmaron. Como lider, reserva la cancha ahora.</p>
                </header>
                <nav class="flex gap-4 w-full md:w-auto">
                    <button wire:click="exitLobby" wire:confirm="¿Disolver el equipo y salir?" class="flex-1 md:flex-none px-8 py-4 bg-gray-800 hover:bg-red-900 text-white font-bold rounded-xl border border-gray-600 transition uppercase text-xs">
                        SALIR DEL LOBBY
                    </button>
                    <a href="{{ route('reservas.create', ['lobby_id' => $lobby->id]) }}" class="flex-1 md:flex-none px-12 py-4 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-xl rounded-xl shadow-[0_0_30px_rgba(234,179,8,0.5)] transform hover:scale-105 transition flex items-center justify-center gap-2 uppercase">
                        ⚽ INICIAR JUEGO
                    </a>
                </nav>
            </section>
        </aside>
    @endif
</section>