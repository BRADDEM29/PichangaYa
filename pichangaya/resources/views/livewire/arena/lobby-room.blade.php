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

                <figure class="h-10 w-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center text-xl font-bold shadow-lg" aria-hidden="true">
                    ⚽
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
        
        {{-- SECTION: Equipos (Columna Izquierda) --}}
        <section class="lg:col-span-8 space-y-6" aria-label="Equipos">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- ARTICLE: Equipo A --}}
                <article class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border border-gray-700/50">
                    <header class="bg-gray-700/50 p-4 border-b border-gray-700 flex justify-between items-center">
                        <h2 class="font-bold text-blue-400">EQUIPO A</h2>
                        <span class="text-xs bg-black/40 px-2 py-1 rounded text-gray-400">{{ $lobby->slots->where('team_side', 'A')->count() }}/7</span>
                    </header>
                    
                    <ul class="p-4 space-y-3">
                        @foreach($lobby->slots->where('team_side', 'A') as $slot)
                            <li class="flex items-center justify-between bg-gray-900/40 p-2 rounded-lg border border-gray-700/30">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $slot->user->profile_photo_url }}" class="w-10 h-10 rounded-full border-2 {{ $slot->user_id == Auth::id() ? 'border-blue-500' : 'border-gray-600' }}" alt="{{ $slot->user->name }}">
                                    <div>
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">{{ $slot->user->name }}</p>
                                        @if($slot->is_captain) <span class="text-[10px] text-yellow-500 leading-none font-bold">👑 Líder</span> @endif
                                    </div>
                                </div>
                                
                                {{-- Controles --}}
                                @if($slot->user_id === Auth::id())
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleCaptain" class="p-1.5 rounded-lg transition {{ $slot->is_captain ? 'bg-yellow-500/20 text-yellow-500 hover:bg-yellow-500/40' : 'bg-gray-700 text-gray-400 hover:bg-gray-600 hover:text-white' }}" title="Liderazgo">
                                            @if($slot->is_captain)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </button>
                                        <button wire:click="switchTeam" class="p-1.5 bg-gray-700 text-blue-400 hover:bg-blue-600 hover:text-white rounded-lg transition" title="Cambiar a Equipo B">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <div>@if($slot->confirmed_at) ✅ @else <span class="w-2 h-2 rounded-full bg-gray-500 inline-block"></span> @endif</div>
                                @endif
                            </li>
                        @endforeach
                        
                        {{-- Slots Vacíos --}}
                        @for($i = $lobby->slots->where('team_side', 'A')->count(); $i < 7; $i++)
                            <li class="flex items-center justify-center p-3 rounded-lg border border-dashed border-gray-800 text-gray-600 text-xs font-mono">
                                ESPERANDO JUGADOR...
                            </li>
                        @endfor
                    </ul>

                    @if(!$lobby->slots->where('user_id', Auth::id())->count() && $lobby->slots->where('team_side', 'A')->count() < 7)
                        <footer class="p-4 pt-0">
                            <button wire:click="joinTeam('A')" class="w-full py-2 bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 border border-blue-500/50 rounded-lg text-xs font-bold uppercase tracking-wider transition">
                                Unirse al Equipo A
                            </button>
                        </footer>
                    @endif
                </article>

                {{-- ARTICLE: Equipo B --}}
                <article class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border border-gray-700/50">
                    <header class="bg-gray-700/50 p-4 border-b border-gray-700 flex justify-between items-center">
                        <h2 class="font-bold text-red-400">EQUIPO B</h2>
                        <span class="text-xs bg-black/40 px-2 py-1 rounded text-gray-400">{{ $lobby->slots->where('team_side', 'B')->count() }}/7</span>
                    </header>
                    
                    <ul class="p-4 space-y-3">
                        @foreach($lobby->slots->where('team_side', 'B') as $slot)
                            <li class="flex items-center justify-between bg-gray-900/40 p-2 rounded-lg border border-gray-700/30">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $slot->user->profile_photo_url }}" class="w-10 h-10 rounded-full border-2 {{ $slot->user_id == Auth::id() ? 'border-red-500' : 'border-gray-600' }}" alt="{{ $slot->user->name }}">
                                    <div>
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">{{ $slot->user->name }}</p>
                                        @if($slot->is_captain) <span class="text-[10px] text-yellow-500 leading-none font-bold">👑 Líder</span> @endif
                                    </div>
                                </div>

                                @if($slot->user_id === Auth::id())
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleCaptain" class="p-1.5 rounded-lg transition {{ $slot->is_captain ? 'bg-yellow-500/20 text-yellow-500 hover:bg-yellow-500/40' : 'bg-gray-700 text-gray-400 hover:bg-gray-600 hover:text-white' }}" title="Liderazgo">
                                            @if($slot->is_captain)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </button>
                                        <button wire:click="switchTeam" class="p-1.5 bg-gray-700 text-red-400 hover:bg-red-600 hover:text-white rounded-lg transition transform rotate-180" title="Cambiar a Equipo A">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <div>@if($slot->confirmed_at) ✅ @else <span class="w-2 h-2 rounded-full bg-gray-500 inline-block"></span> @endif</div>
                                @endif
                            </li>
                        @endforeach

                        @for($i = $lobby->slots->where('team_side', 'B')->count(); $i < 7; $i++)
                            <li class="flex items-center justify-center p-3 rounded-lg border border-dashed border-gray-800 text-gray-600 text-xs font-mono">
                                ESPERANDO JUGADOR...
                            </li>
                        @endfor
                    </ul>

                    @if(!$lobby->slots->where('user_id', Auth::id())->count() && $lobby->slots->where('team_side', 'B')->count() < 7)
                        <footer class="p-4 pt-0">
                            <button wire:click="joinTeam('B')" class="w-full py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/50 rounded-lg text-xs font-bold uppercase tracking-wider transition">
                                Unirse al Equipo B
                            </button>
                        </footer>
                    @endif
                </article>
            </div>
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

            {{-- FOOTER: Botón Salir --}}
            <footer class="text-center pt-4">
                <button wire:click="exitLobby" 
                        wire:confirm="¿Seguro que quieres abandonar la sala?"
                        class="text-sm text-red-400 hover:text-red-300 underline font-bold cursor-pointer transition">
                    ❌ SALIR DE LA SALA DEFINITIVAMENTE
                </button>
            </footer>
        </aside>

    </main>
</div>