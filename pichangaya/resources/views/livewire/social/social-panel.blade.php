<aside class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3 pointer-events-none" 
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
    
    {{-- 🟢 BOTÓN DE ACCESO AL LOBBY (Si estás en partida) --}}
    @if($activeLobby)
        <a href="{{ route('lobby.show', $activeLobby->id) }}" wire:navigate 
           class="pointer-events-auto flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-full shadow-lg border border-green-400 animate-bounce transition-transform hover:scale-105">
            <span class="relative flex h-3 w-3" aria-hidden="true">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
            <span class="text-xs font-bold uppercase tracking-wider">En Partida</span>
        </a>
    @endif

    {{-- 🔵 PANEL PRINCIPAL --}}
    @if($isOpen)
        <section class="pointer-events-auto w-80 sm:w-96 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col max-h-[70vh] sm:max-h-[600px] transition-all animate-in slide-in-from-bottom-5 fade-in">
            
            {{-- HEADER Y NAVEGACIÓN --}}
            <header class="bg-blue-600 p-4 shadow-md z-10 shrink-0">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2">
                        @if($activeChatUser)
                            {{-- Botón volver --}}
                            <button wire:click="closeChat" class="text-white hover:text-blue-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            {{-- Info Amigo --}}
                            <div class="flex items-center gap-2">
                                <img src="{{ $activeChatUser->profile_photo_url }}" class="w-8 h-8 rounded-full border border-white/30" alt="">
                                <h3 class="text-sm font-bold text-white truncate max-w-[150px]">{{ $activeChatUser->name }}</h3>
                            </div>
                        @else
                            {{-- Título Principal --}}
                            <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Social
                            </h3>
                        @endif
                    </div>

                    {{-- Cerrar Panel --}}
                    <button wire:click="toggle" class="text-white/70 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- BUSCADOR (Solo si no hay chat abierto) --}}
                @if(!$activeChatUser)
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" 
                               placeholder="Buscar usuario para agregar..." 
                               class="w-full bg-blue-700/50 text-white placeholder-blue-200 text-xs border-none rounded-lg py-2 pl-8 focus:ring-1 focus:ring-white">
                        <svg class="w-4 h-4 text-blue-200 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                @endif
            </header>

            {{-- CUERPO DEL PANEL --}}
            <main class="flex-1 overflow-hidden relative bg-gray-50 dark:bg-gray-800 flex flex-col">
                
                @if($activeChatUser)
                    {{-- 🟢 VISTA DE CHAT PRIVADO --}}
                    <ul x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        @forelse($chatMessages as $msg)
                            <li class="flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }} animate-in fade-in slide-in-from-bottom-2">
                                <span class="px-4 py-2 rounded-2xl text-sm max-w-[85%] shadow-sm {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-bl-none' }}">
                                    {{ $msg->content }}
                                </span>
                                <span class="text-[10px] text-gray-400 mt-1 px-1">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </li>
                        @empty
                            <li class="h-full flex flex-col items-center justify-center opacity-40">
                                <p class="text-xs text-gray-500 font-medium">Inicia la conversación</p>
                            </li>
                        @endforelse
                    </ul>

                    <footer class="p-3 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shrink-0">
                        <form wire:submit.prevent="sendMessage" class="flex gap-2">
                            <input type="text" wire:model="newMessage" placeholder="Escribe un mensaje..." class="flex-1 bg-gray-100 dark:bg-gray-800 border-0 rounded-full text-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:text-white transition">
                            <button type="submit" class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-md transition">
                                <svg class="w-5 h-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </form>
                    </footer>

                @else
                    {{-- 🟢 LISTA DE RESULTADOS DE BÚSQUEDA O AMIGOS --}}
                    <div class="h-full overflow-y-auto p-2 space-y-1 custom-scrollbar">
                        
                        {{-- RESULTADOS DE BÚSQUEDA --}}
                        @if(strlen($searchQuery) > 2)
                            <div class="mb-4">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase px-2 mb-2">Resultados de búsqueda</h4>
                                @forelse($searchResults as $result)
                                    <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $result->profile_photo_url }}" class="w-8 h-8 rounded-full" alt="">
                                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $result->name }}</span>
                                        </div>
                                        
                                        @if($result->friend_status === 'none')
                                            <button wire:click="addFriend({{ $result->id }})" class="text-[10px] bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded-full font-bold transition">
                                                Agregar
                                            </button>
                                        @elseif($result->friend_status === 'sent')
                                            <span class="text-[10px] text-gray-500 italic">Enviada</span>
                                        @elseif($result->friend_status === 'received')
                                            <button wire:click="acceptFriend({{ $result->id }})" class="text-[10px] bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded-full font-bold transition">
                                                Aceptar
                                            </button>
                                        @elseif($result->friend_status === 'accepted')
                                            <span class="text-[10px] text-green-500 font-bold">Amigos</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-center text-gray-500 py-2">No se encontraron usuarios.</p>
                                @endforelse
                            </div>
                        @endif

                        {{-- LISTA DE AMIGOS CONECTADOS/DESCONECTADOS --}}
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase px-2 mb-2">Mis Amigos</h4>
                        @forelse($friends as $friend)
                            <button wire:click="openChat({{ $friend->id }})" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition group">
                                <div class="relative">
                                    <img src="{{ $friend->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                    {{-- Si tienes campo is_online en BD úsalo, si no, quitamos el punto verde por ahora --}}
                                    {{-- <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></span> --}}
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-500 transition">{{ $friend->name }}</h4>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">Click para chatear</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </button>
                        @empty
                            <div class="flex flex-col items-center justify-center pt-10 opacity-50">
                                <div class="bg-gray-200 dark:bg-gray-700 p-4 rounded-full mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">Aún no tienes amigos agregados.</p>
                                <p class="text-[10px] text-gray-400 mt-1">Usa el buscador para añadir gente.</p>
                            </div>
                        @endforelse

                    </div>
                @endif
            </main>
        </section>
    @endif

    {{-- 🔵 BOTÓN FLOTANTE --}}
    <button wire:click="toggle" class="pointer-events-auto h-14 w-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110 relative z-50">
        @if($isOpen)
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        @else
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        @endif
    </button>
</aside>