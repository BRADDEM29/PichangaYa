<section class="fixed inset-0 z-50 bg-[#0f172a] text-white overflow-y-auto custom-scrollbar flex flex-col" wire:poll.3s="checkLobbyStatus">
    
    {{-- MODAL DE PARTIDA ENCONTRADA --}}
    @if($lobby->status === 'ready_to_play')
        <dialog open class="fixed inset-0 z-[60] flex items-center justify-center w-full h-full bg-black/90 backdrop-blur-md p-4 animate-in fade-in">
            <article class="bg-gray-900 border-2 border-green-500 rounded-2xl p-8 max-w-lg w-full text-center shadow-[0_0_50px_rgba(34,197,94,0.3)] relative overflow-hidden">
                <figure class="absolute inset-0 bg-green-500/10 animate-pulse pointer-events-none"></figure>
                <header class="relative z-10 space-y-4">
                    <h2 class="text-3xl md:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg">PARTIDA ENCONTRADA</h2>
                    <p class="text-green-400 font-bold tracking-widest text-sm uppercase animate-bounce">Sala Llena • Todos Listos</p>
                </header>
                <footer class="mt-8 relative z-10 space-y-6">
                    <section class="bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                        <p class="text-sm text-gray-300 font-mono">Deporte: <span class="text-white font-bold uppercase">{{ $lobby->sport->name }}</span></p>
                        <p class="text-sm text-gray-300 font-mono mt-1">Jugadores Listos: <span class="text-green-400 font-bold text-lg">{{ $confirmedCount }}/{{ $maxPlayers }}</span></p>
                    </section>
                    <nav class="grid grid-cols-2 gap-4">
                        <button wire:click="exitLobby" wire:confirm="¿Rechazar y salir?" class="py-4 bg-gray-800 hover:bg-red-900 border border-gray-600 text-gray-300 hover:text-white font-bold rounded-xl uppercase tracking-widest transition shadow-lg text-sm">RECHAZAR</button>
                        <a href="{{ route('reservas.user.index') }}" class="py-4 bg-green-500 hover:bg-green-400 text-black font-black text-xl rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.5)] transform hover:scale-105 transition uppercase flex justify-center items-center gap-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>ACEPTAR</span>
                        </a>
                    </nav>
                </footer>
            </article>
        </dialog>
    @endif

    {{-- BARRA DE NAVEGACIÓN SUPERIOR --}}
    <nav class="relative z-40 bg-gray-900 shadow-md">
        <livewire:navigation-menu />
    </nav>

    {{-- HEADER DEL LOBBY --}}
    <header class="bg-gray-800 border-b border-gray-700 py-4 px-4 shadow-lg sticky top-0 z-30">
        <section class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter flex items-center gap-2">
                <span class="text-blue-500">Lobby</span> #{{ $lobby->id }}
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded not-italic font-sans">{{ $lobby->sport->name }}</span>
            </h1>
            <aside class="flex items-center gap-6 bg-gray-900/80 px-6 py-2 rounded-full border border-gray-700">
                <figure class="text-center">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest">Estado</span>
                    <span class="text-sm font-bold {{ $lobby->status === 'searching' ? 'text-blue-400' : 'text-green-400' }} uppercase">{{ $lobby->status === 'searching' ? 'Buscando' : 'Confirmando' }}</span>
                </figure>
                <figure class="w-px h-8 bg-gray-700"></figure>
                <figure class="text-center">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest">Jugadores</span>
                    <span class="text-xl font-mono font-bold text-white">{{ $playerCount }}/{{ $maxPlayers }}</span>
                </figure>
            </aside>
        </section>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- ZONA DE EQUIPOS (IZQUIERDA) --}}
        <section class="lg:col-span-8 space-y-8" aria-label="Zona de Equipos">
            @php
                $slotsPerTeam = max(1, intdiv($maxPlayers, 2)); 
                $teamA = $lobby->slots->where('team_side', 'A')->values();
                $teamB = $lobby->slots->where('team_side', 'B')->values();
            @endphp

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- EQUIPO A --}}
                <article class="bg-gray-800/50 rounded-2xl border border-gray-700 overflow-hidden flex flex-col min-h-[400px]">
                    <header class="bg-gray-900/80 p-4 border-b border-gray-700 flex justify-between items-center border-l-4 border-l-blue-500">
                        <h3 class="text-sm font-black uppercase tracking-wider text-blue-400">Equipo A</h3>
                        <span class="text-xs bg-gray-800 px-2 py-0.5 rounded text-gray-400 font-mono">{{ $teamA->count() }}/{{ $slotsPerTeam }}</span>
                    </header>
                    <ul class="p-4 space-y-3 flex-1">
                        @for ($i = 0; $i < $slotsPerTeam; $i++)
                            @if(isset($teamA[$i]))
                                <li class="flex items-center justify-between bg-gray-700/50 p-3 rounded-lg border border-gray-600 shadow-sm animate-in fade-in">
                                    <figure class="flex items-center gap-3 flex-1">
                                        <img src="{{ $teamA[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-500">
                                        <figcaption class="flex flex-col">
                                            <span class="text-xs font-bold text-white truncate max-w-[100px]">{{ $teamA[$i]->user->name }}</span>
                                            <span class="text-[10px] {{ $teamA[$i]->confirmed_at ? 'text-green-400' : 'text-gray-500' }}">{{ $teamA[$i]->confirmed_at ? 'Confirmado' : 'Pendiente' }}</span>
                                        </figcaption>
                                    </figure>
                                    <aside class="flex items-center gap-2">
                                        @if($teamA[$i]->user_id === auth()->id())
                                            <button wire:click="toggleReady" class="w-8 h-8 rounded-lg border-2 flex items-center justify-center transition-all shadow-lg {{ $teamA[$i]->confirmed_at ? 'bg-green-500 border-green-400 text-black' : 'bg-gray-800 border-gray-500 text-gray-500 hover:border-green-400 hover:text-green-400' }}">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        @else
                                            <span class="w-8 h-8 rounded-lg border-2 flex items-center justify-center {{ $teamA[$i]->confirmed_at ? 'bg-green-500/20 border-green-500 text-green-500' : 'bg-gray-800 border-gray-600 text-gray-600' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        @endif
                                    </aside>
                                </li>
                            @else
                                <li class="h-14 border border-dashed border-gray-700 rounded-lg bg-gray-800/30 flex items-center justify-center cursor-pointer hover:border-blue-500/50 hover:text-blue-400 text-gray-600 transition group" wire:click="moveToTeam('A')">
                                    <span class="text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Unirse
                                    </span>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>

                {{-- EQUIPO B --}}
                <article class="bg-gray-800/50 rounded-2xl border border-gray-700 overflow-hidden flex flex-col min-h-[400px]">
                    <header class="bg-gray-900/80 p-4 border-b border-gray-700 flex justify-between items-center border-r-4 border-r-red-500">
                        <h3 class="text-sm font-black uppercase tracking-wider text-red-400">Equipo B</h3>
                        <span class="text-xs bg-gray-800 px-2 py-0.5 rounded text-gray-400 font-mono">{{ $teamB->count() }}/{{ $slotsPerTeam }}</span>
                    </header>
                    <ul class="p-4 space-y-3 flex-1">
                        @for ($i = 0; $i < $slotsPerTeam; $i++)
                            @if(isset($teamB[$i]))
                                <li class="flex items-center justify-between bg-gray-700/50 p-3 rounded-lg border border-gray-600 shadow-sm animate-in fade-in">
                                    <figure class="flex items-center gap-3 flex-1">
                                        <img src="{{ $teamB[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-500">
                                        <figcaption class="flex flex-col">
                                            <span class="text-xs font-bold text-white truncate max-w-[100px]">{{ $teamB[$i]->user->name }}</span>
                                            <span class="text-[10px] {{ $teamB[$i]->confirmed_at ? 'text-green-400' : 'text-gray-500' }}">{{ $teamB[$i]->confirmed_at ? 'Confirmado' : 'Pendiente' }}</span>
                                        </figcaption>
                                    </figure>
                                    <aside class="flex items-center gap-2">
                                        @if($teamB[$i]->user_id === auth()->id())
                                            <button wire:click="toggleReady" class="w-8 h-8 rounded-lg border-2 flex items-center justify-center transition-all shadow-lg {{ $teamB[$i]->confirmed_at ? 'bg-green-500 border-green-400 text-black' : 'bg-gray-800 border-gray-500 text-gray-500 hover:border-green-400 hover:text-green-400' }}">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        @else
                                            <span class="w-8 h-8 rounded-lg border-2 flex items-center justify-center {{ $teamB[$i]->confirmed_at ? 'bg-green-500/20 border-green-500 text-green-500' : 'bg-gray-800 border-gray-600 text-gray-600' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        @endif
                                    </aside>
                                </li>
                            @else
                                <li class="h-14 border border-dashed border-gray-700 rounded-lg bg-gray-800/30 flex items-center justify-center cursor-pointer hover:border-red-500/50 hover:text-red-400 text-gray-600 transition group" wire:click="moveToTeam('B')">
                                    <span class="text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Unirse
                                    </span>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>
            </section>
        </section>

        {{-- BARRA LATERAL (CARRUSEL + CHAT) --}}
        <aside class="lg:col-span-4 flex flex-col gap-6 h-full">
            
            {{-- CARRUSEL DE CANCHAS AGRANDADO --}}
            @if($carouselItems && $carouselItems->count() > 0)
                <section class="bg-gray-800 rounded-2xl p-4 border border-gray-700 shadow-xl overflow-hidden">
                    <header class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Canchas Disponibles</h3>
                    </header>
                    <section class="flex flex-col gap-4">
                        @foreach($carouselItems as $cancha)
                            {{-- 🟢 CAMBIO: Altura h-96 para mayor impacto --}}
                            <article class="group relative h-96 bg-gray-900 rounded-2xl overflow-hidden border border-gray-700 cursor-pointer hover:border-yellow-400 transition-all shadow-2xl transform hover:scale-[1.01]">
                                @if($cancha->media->first())
                                    <img src="{{ $cancha->media->first()->getUrl() }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <span class="w-full h-full flex items-center justify-center bg-gray-800 text-gray-500 text-xs uppercase tracking-widest">Sin Foto</span>
                                @endif
                                
                                {{-- Degradado ajustado para mejor legibilidad --}}
                                <footer class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent flex flex-col justify-end p-6">
                                    <h4 class="text-2xl font-black text-white leading-none mb-2 group-hover:text-yellow-400 transition uppercase italic tracking-tighter">
                                        {{ $cancha->name }}
                                    </h4>
                                    <div class="flex items-center gap-2 text-gray-300">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <p class="text-xs font-bold uppercase tracking-widest">{{ $cancha->district->name }}</p>
                                    </div>
                                </footer>
                            </article>
                        @endforeach
                    </section>
                </section>
            @endif

            {{-- CHAT --}}
            <section class="flex-1 bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden flex flex-col shadow-xl min-h-[400px]">
                <header class="bg-gray-900 p-4 border-b border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Chat de Sala</h3>
                </header>
                <ul class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar bg-gray-800/50">
                    @forelse($lobbyMessages as $msg)
                        <li class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <span class="text-[10px] text-gray-500 mb-1 px-1">{{ $msg->sender->name }}</span>
                            <p class="max-w-[85%] px-3 py-2 rounded-xl text-sm shadow-sm {{ $msg->user_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-700 text-gray-200 rounded-bl-none' }}">
                                {{ $msg->content }}
                            </p>
                        </li>
                    @empty
                        <li class="h-full flex flex-col items-center justify-center opacity-30 text-xs text-gray-500">
                            <span>No hay mensajes...</span>
                        </li>
                    @endforelse
                </ul>
                <footer class="p-3 bg-gray-900 border-t border-gray-700">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <input type="text" wire:model="newMessage" placeholder="Escribe aquí..." class="flex-1 bg-gray-800 border-gray-700 rounded-lg text-sm text-white px-3 py-2 outline-none focus:border-blue-500 transition">
                        <button type="submit" class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                </footer>
            </section>

            <footer class="space-y-3">
                <button wire:click="exitLobby" wire:confirm="¿Abandonar la sala?" class="w-full py-4 bg-red-900/20 hover:bg-red-600/30 text-red-400 hover:text-red-200 font-bold border border-red-900/50 rounded-xl transition text-xs uppercase tracking-widest flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Abandonar Sala</span>
                </button>
            </footer>
        </aside>
    </main>
</section>