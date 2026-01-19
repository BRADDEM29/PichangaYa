<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3" 
     x-data="{ 
        scrollToBottom() { 
            if (this.$refs.chatContainer) {
                this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
            }
        } 
     }"
     @scroll-bottom.window="setTimeout(() => scrollToBottom(), 100)"
>
    
    {{-- BOTÓN LOBBY --}}
    @if($activeLobby)
        <a href="{{ route('lobby.show', $activeLobby->id) }}" wire:navigate 
           class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-full shadow-lg border border-green-400 animate-bounce">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
            <span class="text-xs font-bold uppercase tracking-wider">En Partida</span>
        </a>
    @endif

    {{-- PANEL PRINCIPAL --}}
    @if($isOpen)
        <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-2xl w-80 overflow-hidden mb-2 flex flex-col h-[450px]">
            
            {{-- 🟢 ESCENA 1: LISTA DE AMIGOS --}}
            @if(!$activeChatUser)
                
                {{-- Header --}}
                <div class="bg-gray-800 p-3 border-b border-gray-700 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-white text-sm">Social</h3>
                    <button wire:click="toggle" class="text-gray-400 hover:text-white">✖</button>
                </div>

                {{-- Buscador --}}
                <div class="p-2 shrink-0">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Buscar jugador..." 
                        class="w-full bg-gray-950 text-white text-xs p-2 rounded border border-gray-700 focus:border-blue-500 placeholder-gray-500">
                </div>

                {{-- Lista --}}
                <div class="overflow-y-auto p-2 space-y-2 flex-1">
                    
                    {{-- Resultados Búsqueda --}}
                    @if(strlen($searchQuery) > 2)
                        <div class="mb-3 border-b border-gray-700 pb-2">
                            <p class="text-[10px] text-blue-400 uppercase font-bold mb-1 ml-1">Resultados</p>
                            @forelse($searchResults as $result)
                                {{-- IMPORTANTE: wire:key para evitar errores --}}
                                <div wire:key="search-{{ $result->id }}" class="flex justify-between items-center bg-gray-800/50 p-2 rounded border border-gray-700/50">
                                    <span class="text-gray-200 text-xs truncate w-24">{{ $result->name }}</span>
                                    @if($result->friend_status === 'none')
                                        <button wire:click="addFriend({{ $result->id }})" class="text-xs bg-blue-600 text-white px-2 py-1 rounded">+ Add</button>
                                    @elseif($result->friend_status === 'received')
                                        <button wire:click="acceptFriend({{ $result->id }})" class="text-xs bg-green-600 text-white px-2 py-1 rounded">✔ Aceptar</button>
                                    @elseif($result->friend_status === 'friends')
                                        <span class="text-xs text-green-400">Amigo</span>
                                    @else
                                        <span class="text-xs text-gray-500">Pendiente</span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-gray-500">Sin resultados.</p>
                            @endforelse
                        </div>
                    @endif

                    {{-- Lista Mis Amigos --}}
                    <p class="text-[10px] text-gray-500 uppercase font-bold ml-1">Mis Amigos</p>
                    @forelse($friends as $friend)
                        {{-- 🟢 AQUÍ ESTÁ EL FIX CLAVE: wire:key --}}
                        <div wire:key="friend-{{ $friend->id }}" 
                             wire:click="openChat({{ $friend->id }})" 
                             class="flex items-center gap-2 hover:bg-gray-800 p-2 rounded cursor-pointer transition group">
                            
                            <div class="relative">
                                <img src="{{ $friend->profile_photo_url }}" class="w-8 h-8 rounded-full">
                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-gray-900 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-200 font-medium truncate">{{ $friend->name }}</p>
                                <p class="text-[10px] text-gray-400 group-hover:text-blue-400 transition">Clic para chatear</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-6">No tienes amigos conectados.</p>
                    @endforelse
                </div>

            {{-- 🟢 ESCENA 2: CHAT --}}
            @else
                {{-- Header Chat --}}
                <div class="bg-blue-900/40 p-3 border-b border-gray-700 flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-2">
                        <button wire:click="closeChat" class="text-gray-300 hover:text-white mr-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <img src="{{ $activeChatUser->profile_photo_url }}" class="w-6 h-6 rounded-full">
                        <span class="font-bold text-white text-sm truncate w-32">{{ $activeChatUser->name }}</span>
                    </div>
                    <button wire:click="toggle" class="text-gray-400 hover:text-white">✖</button>
                </div>

                {{-- Área de Mensajes --}}
                <div x-ref="chatContainer" wire:poll.3s="loadMessages" class="flex-1 overflow-y-auto p-3 space-y-3 bg-gray-950/50">
                    @forelse($chatMessages as $msg)
                        <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%] rounded-2xl px-3 py-2 text-xs 
                                {{ $msg->sender_id === auth()->id() 
                                    ? 'bg-blue-600 text-white rounded-br-none' 
                                    : 'bg-gray-700 text-gray-200 rounded-bl-none' }}">
                                {{ $msg->content }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-xs text-gray-500">¡Saluda!</p>
                        </div>
                    @endforelse
                </div>

                {{-- Input --}}
                <form wire:submit.prevent="sendMessage" class="p-2 border-t border-gray-700 bg-gray-900">
                    <div class="flex gap-2">
                        <input type="text" wire:model="newMessage" 
                            class="flex-1 bg-gray-800 border-none rounded-full px-4 py-2 text-sm text-white focus:ring-1 focus:ring-blue-500 placeholder-gray-500" 
                            placeholder="Escribe un mensaje...">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded-full shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            @endif

        </div>
    @endif

    {{-- Botón Toggle --}}
    <button wire:click="toggle" class="h-14 w-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110 relative">
        @if($isOpen)
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        @else
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(count(auth()->user()->friends ?? []) > 0)
                <span class="absolute top-0 right-0 h-3.5 w-3.5 bg-green-500 border-2 border-gray-900 rounded-full"></span>
            @endif
        @endif
    </button>
</div>