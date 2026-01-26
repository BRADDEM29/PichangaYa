{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\arena\lobby-room.blade.php --}}

<div class="fixed inset-0 z-50 bg-gray-900 text-white overflow-y-auto" wire:poll.4s>
    
    {{-- NAV: Navegación --}}
    <nav class="relative z-50 bg-gray-900">
        <livewire:navigation-menu />
    </nav>

    {{-- HEADER: Barra Superior --}}
    <header class="bg-gray-800 border-b border-gray-700 shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col md:flex-row justify-between items-center">
            
            <div class="flex items-center gap-4">
                {{-- Botón VOLVER --}}
                <a href="{{ route('arena.index') }}" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-full text-gray-300 transition" title="Volver al menú">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>

                {{-- 🟢 ICONO BALÓN --}}
                <figure class="h-10 w-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg p-2" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ball-football"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7l4.76 3.45l-1.76 5.55h-6l-1.76 -5.55l4.76 -3.45" /><path d="M12 7v-4m3 13l2.5 3m-.74 -8.55l3.74 -1.45m-11.44 7.05l-2.56 2.95m.74 -8.55l-3.74 -1.45" /></svg>
                </figure>
                
                <hgroup>
                    <h1 class="text-lg font-black tracking-wide text-white uppercase">
                        Sala #{{ $lobby->id }} <span class="text-gray-500 mx-2">|</span> {{ $lobby->sport->name }}
                    </h1>
                </hgroup>
            </div>

            {{-- Estado --}}
            <div class="my-2 md:my-0">
                @if($lobby->status === 'searching')
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-bold text-blue-400 animate-pulse uppercase tracking-widest">Buscando Jugadores...</span>
                        <time class="text-[10px] text-gray-500 font-mono bg-gray-900 px-2 py-0.5 rounded mt-1">
                            EXPIRA EN: {{ \Carbon\Carbon::parse($lobby->expires_at)->diffForHumans() }}
                        </time>
                    </div>
                @elseif($lobby->status === 'confirming')
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-bold text-yellow-400 animate-bounce uppercase tracking-widest">⚠️ Confirmando Partida</span>
                    </div>
                @endif
            </div>

            {{-- Contador --}}
            <div class="flex items-center gap-2 bg-gray-900 px-3 py-1 rounded-full border border-gray-700">
                <span class="text-sm font-bold text-gray-300">JUGADORES</span>
                <span class="text-xl font-black {{ $playerCount >= $maxPlayers ? 'text-green-500' : 'text-white' }}">
                    {{ $playerCount }}/{{ $maxPlayers }}
                </span>
            </div>
        </div>
    </header>

    {{-- MAIN: Contenido del Lobby --}}
    <main class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- SECTION: Equipos (Columna Izquierda - Estilo MOBA) --}}
        <section class="lg:col-span-8 space-y-6" aria-label="Equipos">
            
            @php
                $teamA = $lobby->slots->where('team_side', 'A')->values();
                $teamB = $lobby->slots->where('team_side', 'B')->values();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- 🟢 EQUIPO A (Local) --}}
                <article class="space-y-2">
                    <header class="text-green-400 font-black uppercase tracking-widest text-sm mb-3 border-b border-green-500/30 pb-2 flex justify-between items-end">
                        <h3>Equipo A (Local)</h3>
                        <span class="text-xs bg-gray-800 px-2 rounded">{{ $teamA->count() }}/7</span>
                    </header>

                    <ul class="space-y-2">
                        @for ($i = 0; $i < 7; $i++)
                            @if(isset($teamA[$i]))
                                {{-- 👤 SLOT OCUPADO --}}
                                <li class="flex items-center justify-between bg-gradient-to-r from-green-900/40 to-gray-800 p-2 rounded border-l-4 border-green-500 transition hover:bg-gray-700 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img src="{{ $teamA[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded shadow-lg border border-gray-600">
                                            @if($teamA[$i]->is_captain)
                                                <span class="absolute -top-1 -left-1 text-xs" title="Capitán">👑</span>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold {{ $teamA[$i]->user_id === auth()->id() ? 'text-white' : 'text-gray-300' }}">
                                                {{ $teamA[$i]->user->name }}
                                            </span>
                                            
                                            {{-- 🟢 ESTADO READY --}}
                                            @if($teamA[$i]->confirmed_at)
                                                <div class="flex items-center gap-1 text-[10px] text-green-400 font-bold uppercase tracking-wider">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                                    Listo
                                                </div>
                                            @else
                                                <span class="text-[9px] text-gray-500 uppercase tracking-wider">⏳ Esperando</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($teamA[$i]->user_id === auth()->id())
                                        <button wire:click="toggleCaptain" class="text-gray-500 hover:text-yellow-400 transition" title="Pedir Capitanía">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        </button>
                                    @endif
                                </li>
                            @else
                                {{-- 🌑 SLOT VACÍO --}}
                                <li class="h-[58px] bg-gray-900/30 border border-gray-700/50 border-dashed rounded flex items-center justify-center group hover:border-green-500/50 transition cursor-pointer"
                                     wire:click="moveToTeam('A')">
                                    @if($userSlot->team_side !== 'A')
                                        <span class="text-[10px] font-bold text-gray-600 group-hover:text-green-400 uppercase tracking-widest transition flex items-center gap-1">
                                            <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Unirse
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-700 font-mono">VACÍO</span>
                                    @endif
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>

                {{-- 🔴 EQUIPO B (Visitante) --}}
                <article class="space-y-2">
                    <header class="text-red-400 font-black uppercase tracking-widest text-sm mb-3 border-b border-red-500/30 pb-2 flex justify-between items-end">
                        <h3>Equipo B (Visitante)</h3>
                        <span class="text-xs bg-gray-800 px-2 rounded">{{ $teamB->count() }}/7</span>
                    </header>

                    <ul class="space-y-2">
                        @for ($i = 0; $i < 7; $i++)
                            @if(isset($teamB[$i]))
                                {{-- 👤 SLOT OCUPADO --}}
                                <li class="flex items-center justify-between bg-gradient-to-l from-red-900/40 to-gray-800 p-2 rounded border-r-4 border-red-500 transition hover:bg-gray-700 shadow-sm">
                                    <div class="flex flex-col items-end flex-1 mr-3">
                                        <span class="text-sm font-bold {{ $teamB[$i]->user_id === auth()->id() ? 'text-white' : 'text-gray-300' }}">
                                            {{ $teamB[$i]->user->name }}
                                        </span>
                                        
                                        {{-- 🟢 ESTADO READY --}}
                                        @if($teamB[$i]->confirmed_at)
                                            <div class="flex items-center gap-1 text-[10px] text-green-400 font-bold uppercase tracking-wider">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                                Listo
                                            </div>
                                        @else
                                            <span class="text-[9px] text-red-300 uppercase tracking-wider">⏳ Esperando</span>
                                        @endif
                                    </div>

                                    <div class="relative flex items-center gap-2">
                                        @if($teamB[$i]->user_id === auth()->id())
                                            <button wire:click="toggleCaptain" class="text-gray-500 hover:text-yellow-400 transition" title="Pedir Capitanía">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                            </button>
                                        @endif

                                        <div class="relative">
                                            <img src="{{ $teamB[$i]->user->profile_photo_url }}" class="w-10 h-10 rounded shadow-lg border border-gray-600">
                                            @if($teamB[$i]->is_captain)
                                                <span class="absolute -top-1 -right-1 text-xs" title="Capitán">👑</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @else
                                {{-- 🌑 SLOT VACÍO --}}
                                <li class="h-[58px] bg-gray-900/30 border border-gray-700/50 border-dashed rounded flex items-center justify-center group hover:border-red-500/50 transition cursor-pointer"
                                     wire:click="moveToTeam('B')">
                                    @if($userSlot->team_side !== 'B')
                                        <span class="text-[10px] font-bold text-gray-600 group-hover:text-red-400 uppercase tracking-widest transition flex items-center gap-1">
                                            <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Unirse
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-700 font-mono">VACÍO</span>
                                    @endif
                                </li>
                            @endif
                        @endfor
                    </ul>
                </article>

            </div>

            {{-- 🚫 ELIMINADO EL FOOTER CON EL BOTÓN 'ESTOY LISTO' ANTIGUO --}}

        </section>

        {{-- ASIDE: Columna Derecha (Carrusel + Chat) --}}
        <aside class="lg:col-span-4 space-y-6">
            
            {{-- SECTION: Carrusel --}}
            <section class="bg-gray-800 rounded-2xl overflow-hidden shadow-lg border border-gray-700" aria-label="Sedes Recomendadas">
                <header class="font-bold text-gray-300 text-sm uppercase tracking-wider p-4 border-b border-gray-700 bg-gray-900/50">
                    <h3>🏟️ Sedes Recomendadas</h3>
                </header>
                @if($carouselItems && $carouselItems->count() > 0)
                    <div class="aspect-video relative">
                        <x-carousel :items="$carouselItems" />
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 text-sm">No se encontraron canchas.</div>
                @endif
            </section>

            {{-- SECTION: Chat --}}
            <section class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden flex flex-col h-[400px] shadow-lg"
                 aria-label="Chat de Sala"
                 x-data="{ scroll() { this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight } }"
                 x-init="scroll()"
                 @scroll-lobby-chat.window="setTimeout(() => scroll(), 100)">
                
                {{-- HEADER: Pestañas --}}
                <header class="flex border-b border-gray-700 bg-gray-900/50">
                    <button wire:click="setChatTab('general')" 
                        class="flex-1 py-3 text-xs font-bold uppercase tracking-widest text-center transition-colors {{ $chatTab === 'general' ? 'bg-gray-800 text-green-400 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-300 hover:bg-gray-800' }}">
                        General
                    </button>

                    @if(auth()->user()->party_id)
                        <button wire:click="setChatTab('party')" 
                            class="flex-1 py-3 text-xs font-bold uppercase tracking-widest text-center transition-colors flex items-center justify-center gap-2 {{ $chatTab === 'party' ? 'bg-gray-800 text-blue-400 border-b-2 border-blue-500' : 'text-gray-500 hover:text-gray-300 hover:bg-gray-800' }}">
                            Grupo <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        </button>
                    @else
                        <div class="flex-1 py-3 text-xs font-bold uppercase tracking-widest text-center text-gray-700 cursor-not-allowed bg-gray-900/20" title="No estás en un grupo">
                            Sin Grupo
                        </div>
                    @endif
                </header>

                {{-- LIST: Mensajes --}}
                <ul x-ref="chatBox" class="flex-1 overflow-y-auto p-3 space-y-2 bg-gray-900/30" role="log" aria-live="polite">
                    @php
                        $msgs = ($chatTab === 'general') ? $lobbyMessages : $partyMessages;
                        $myColor = ($chatTab === 'party') ? 'bg-blue-600' : 'bg-green-600';
                    @endphp

                    @forelse($msgs as $msg)
                        <li class="flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <span class="text-[10px] text-gray-500 mb-0.5 px-1">{{ $msg->sender->name }}</span>
                            <div class="max-w-[85%] px-3 py-2 rounded-lg text-sm break-words shadow-sm {{ $msg->sender_id === auth()->id() ? $myColor . ' text-white' : 'bg-gray-700 text-gray-200' }}">
                                {{ $msg->content }}
                            </div>
                        </li>
                    @empty
                        <li class="h-full flex flex-col items-center justify-center opacity-40">
                            <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $chatTab === 'general' ? 'Sala Silenciosa' : 'Grupo Silencioso' }}</p>
                        </li>
                    @endforelse
                </ul>

                {{-- FOOTER: Input --}}
                <footer class="p-2 bg-gray-800 border-t border-gray-700">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <label for="chat-input" class="sr-only">Escribe un mensaje</label>
                        <input id="chat-input" type="text" wire:model="newMessage" 
                               placeholder="{{ $chatTab === 'party' ? 'Mensaje al grupo...' : 'Mensaje a la sala...' }}" 
                               class="flex-1 bg-gray-900 border-gray-600 rounded text-xs text-white focus:ring-1 focus:ring-blue-500 px-3 py-2 placeholder-gray-500">
                        
                        <button type="submit" class="p-2 rounded text-white transition shadow-lg flex items-center justify-center {{ $chatTab === 'party' ? 'bg-blue-600 hover:bg-blue-500' : 'bg-green-600 hover:bg-green-500' }}" aria-label="Enviar">
                            <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </footer>
            </section>

            {{-- 🟢 FOOTER: Botón Salir Gigante con SVG Nuevo --}}
            <footer class="pt-4">
                <button wire:click="exitLobby" 
                        wire:confirm="¿Seguro que quieres abandonar la sala?"
                        class="w-full py-4 bg-red-600 hover:bg-red-500 text-white font-black text-sm uppercase rounded-xl shadow-lg border-2 border-red-500 transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>
                    SALIR DE LA SALA DEFINITIVAMENTE
                </button>
            </footer>
        </aside>

    </main>

    {{-- 🟢 AQUÍ INVOCAMOS EL MODAL GLOBAL (Cuando status sea 'confirming') --}}
    @include('components.arena.match-accept-modal', [
        'isOpen' => $lobby->status === 'confirming',
        'userSlot' => $userSlot
    ])

</div>