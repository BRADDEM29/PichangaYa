{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\arena\social-panel.blade.php --}}

<aside class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3" 
     aria-label="Panel Social"
     x-data="{ 
        scrollToBottom() { 
            if (this.$refs.chatContainer) {
                this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
            }
        } 
     }"
     @scroll-bottom.window="setTimeout(() => scrollToBottom(), 100)"
>
    
    {{-- 🟢 BOTÓN LOBBY (Si hay partida encontrada) --}}
    @if($activeLobby)
        <a href="{{ route('lobby.show', $activeLobby->id) }}" wire:navigate 
           class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-full shadow-lg border border-green-400 animate-bounce transition-transform hover:scale-105">
            <span class="relative flex h-3 w-3" aria-hidden="true">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
            <span class="text-xs font-bold uppercase tracking-wider">En Partida</span>
        </a>
    @endif

    {{-- 🔴 PANEL PRINCIPAL (Desplegable) --}}
    @if($isOpen)
        <section class="bg-gray-900 border border-gray-700 rounded-lg shadow-2xl w-80 overflow-hidden mb-2 flex flex-col h-[500px]" aria-expanded="true">
            
            {{-- NAV: Pestañas Superiores --}}
            @if(!$activeChatUser)
                <nav class="flex bg-gray-800 border-b border-gray-700 shrink-0">
                    <button wire:click="$set('activeTab', 'friends')" 
                        class="flex-1 py-3 text-[10px] font-bold uppercase tracking-widest transition {{ $activeTab === 'friends' ? 'text-blue-500 border-b-2 border-blue-500 bg-blue-500/5' : 'text-gray-500 hover:text-gray-300' }}">
                        Amigos
                    </button>
                    <button wire:click="$set('activeTab', 'party')" 
                        class="flex-1 py-3 text-[10px] font-bold uppercase tracking-widest transition {{ $activeTab === 'party' ? 'text-blue-500 border-b-2 border-blue-500 bg-blue-500/5' : 'text-gray-500 hover:text-gray-300' }}">
                        Mi Grupo
                    </button>
                </nav>
            @endif

            <div class="flex-1 flex flex-col overflow-hidden">
                
                @if($activeTab === 'friends')
                    {{-- 🟢 VISTA DE CHAT ACTIVO --}}
                    @if($activeChatUser)
                        {{-- HEADER CHAT --}}
                        <header class="bg-blue-900/40 p-3 border-b border-gray-700 flex justify-between items-center shrink-0">
                            <div class="flex items-center gap-2">
                                <button wire:click="closeChat" class="text-gray-300 hover:text-white mr-1" aria-label="Volver a lista de amigos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <img src="{{ $activeChatUser->profile_photo_url }}" class="w-6 h-6 rounded-full" alt="">
                                <span class="font-bold text-white text-sm truncate w-32">{{ $activeChatUser->name }}</span>
                            </div>
                            <button wire:click="toggle" class="text-gray-400 hover:text-white" aria-label="Cerrar panel">✖</button>
                        </header>

                        {{-- LISTA DE MENSAJES --}}
                        <ul x-ref="chatContainer" wire:poll.3s="loadMessages" class="flex-1 overflow-y-auto p-3 space-y-3 bg-gray-950/50" role="log" aria-live="polite">
                            @forelse($chatMessages as $msg)
                                <li class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[80%] rounded-2xl px-3 py-2 text-xs 
                                        {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-700 text-gray-200 rounded-bl-none' }}">
                                        {{ $msg->content }}
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-10"><p class="text-xs text-gray-500">¡Saluda a {{ $activeChatUser->name }}!</p></li>
                            @endforelse
                        </ul>

                        {{-- FOOTER CHAT (INPUT) --}}
                        <footer class="p-2 border-t border-gray-700 bg-gray-900">
                            <form wire:submit.prevent="sendMessage">
                                <div class="flex gap-2">
                                    <label for="chat-input" class="sr-only">Escribe un mensaje</label>
                                    <input id="chat-input" type="text" wire:model="newMessage" class="flex-1 bg-gray-800 border-none rounded-full px-4 py-2 text-xs text-white focus:ring-1 focus:ring-blue-500" placeholder="Escribe...">
                                    <button type="submit" class="bg-blue-600 text-white p-2 rounded-full shadow-lg hover:bg-blue-500 transition" aria-label="Enviar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </footer>

                    {{-- 🟢 LISTA DE AMIGOS Y BUSCADOR --}}
                    @else
                        <header class="p-2 shrink-0">
                            <label for="search-friend" class="sr-only">Buscar jugador</label>
                            <input id="search-friend" type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Buscar jugador..." 
                                class="w-full bg-gray-950 text-white text-xs p-2 rounded border border-gray-700 focus:border-blue-500">
                        </header>

                        <div class="overflow-y-auto p-2 space-y-2 flex-1">
                            @if(strlen($searchQuery) > 2)
                                <section class="mb-3 border-b border-gray-700 pb-2">
                                    <h4 class="text-[10px] text-blue-400 uppercase font-bold mb-1">Resultados</h4>
                                    <ul class="space-y-1">
                                        @foreach($searchResults as $result)
                                            <li wire:key="search-{{ $result->id }}" class="flex justify-between items-center bg-gray-800/50 p-2 rounded">
                                                <span class="text-gray-200 text-xs truncate w-24">{{ $result->name }}</span>
                                                @if($result->friend_status === 'none')
                                                    <button wire:click="addFriend({{ $result->id }})" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-500 transition">+ Agregar</button>
                                                @elseif($result->friend_status === 'received')
                                                    <button wire:click="acceptFriend({{ $result->id }})" class="text-[10px] bg-green-600 text-white px-2 py-1 rounded hover:bg-green-500 transition">Aceptar</button>
                                                @else
                                                    <span class="text-[10px] text-gray-500 uppercase">{{ $result->friend_status }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </section>
                            @endif

                            <h4 class="text-[10px] text-gray-500 uppercase font-bold ml-1">Conectados</h4>
                            <ul class="space-y-1">
                                @forelse($friends as $friend)
                                    <li wire:key="friend-{{ $friend->id }}" wire:click="openChat({{ $friend->id }})" 
                                         class="flex items-center gap-2 hover:bg-gray-800 p-2 rounded cursor-pointer transition group">
                                        <figure class="relative">
                                            <img src="{{ $friend->profile_photo_url }}" class="w-8 h-8 rounded-full" alt="{{ $friend->name }}">
                                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-gray-900 rounded-full" aria-label="Online"></span>
                                        </figure>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-200 font-medium truncate">{{ $friend->name }}</p>
                                            <p class="text-[9px] text-gray-400 group-hover:text-blue-400">Clic para chatear</p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-500 text-center py-6">No hay amigos conectados.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endif

                @else
                    {{-- 🟢 SECCIÓN DE PARTY (MI GRUPO) --}}
                    <section class="flex-1 overflow-y-auto p-4">
                        @if($myParty)
                            <div class="space-y-4">
                                <article class="bg-blue-600/10 border border-blue-500/30 rounded p-4 text-center">
                                    <h4 class="text-[9px] text-blue-400 uppercase font-black tracking-tighter">Código de Grupo</h4>
                                    <p class="text-3xl font-mono font-bold text-white tracking-widest selection:bg-blue-500">{{ $myParty->invite_code }}</p>
                                </article>

                                <div class="space-y-2">
                                    <h4 class="text-[10px] text-gray-500 uppercase font-bold">Miembros ({{ $myParty->members->count() }}/5)</h4>
                                    <ul class="space-y-1">
                                        @foreach($myParty->members as $member)
                                            <li class="flex items-center justify-between bg-gray-800/50 p-2 rounded border border-gray-700/50">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $member->profile_photo_url }}" class="w-6 h-6 rounded-full" alt="">
                                                    <span class="text-xs text-white">{{ $member->name }}</span>
                                                </div>
                                                @if($myParty->leader_id === $member->id)
                                                    <span class="text-[9px] bg-yellow-600/20 text-yellow-500 border border-yellow-500/50 px-1 rounded font-bold text-[8px]">LÍDER</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <button wire:click="leaveParty" class="w-full py-2 bg-red-600/10 text-red-500 border border-red-500/20 hover:bg-red-600 hover:text-white text-xs font-bold rounded transition mt-4">
                                    ABANDONAR GRUPO
                                </button>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full space-y-4 px-2">
                                <div class="text-center mb-4">
                                    <div class="bg-gray-800 p-4 rounded-full mb-2 inline-block">
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-400 text-xs">No estás en ningún grupo</p>
                                </div>

                                <button wire:click="createParty" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded shadow-lg transition">
                                    CREAR NUEVO GRUPO
                                </button>
                                
                                <div class="w-full flex items-center gap-2 py-2">
                                    <div class="h-px bg-gray-800 flex-1"></div>
                                    <span class="text-[10px] text-gray-600 font-bold uppercase">Unirse</span>
                                    <div class="h-px bg-gray-800 flex-1"></div>
                                </div>

                                <div class="w-full space-y-2">
                                    <label for="party-code" class="sr-only">Código de invitación</label>
                                    <input id="party-code" type="text" wire:model="inviteCodeInput" placeholder="EJ: XJ82NS" class="w-full bg-gray-800 border-none rounded text-center font-mono text-white text-sm focus:ring-1 focus:ring-blue-500 uppercase">
                                    <button wire:click="joinParty" class="w-full py-2 bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold rounded">
                                        ENTRAR CON CÓDIGO
                                    </button>
                                    @if (session()->has('party_error'))
                                        <p class="text-[10px] text-red-500 text-center" role="alert">{{ session('party_error') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </section>
                @endif
            </div>

        </section>
    @endif

    {{-- 🔵 BOTÓN PRINCIPAL (TOGGLE) --}}
    <button wire:click="toggle" class="h-14 w-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110 relative" aria-label="{{ $isOpen ? 'Cerrar panel social' : 'Abrir panel social' }}">
        @if($isOpen)
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        @else
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(count($friends) > 0)
                <span class="absolute top-0 right-0 h-3.5 w-3.5 bg-green-500 border-2 border-gray-900 rounded-full" aria-label="Amigos conectados"></span>
            @endif
        @endif
    </button>
</aside>