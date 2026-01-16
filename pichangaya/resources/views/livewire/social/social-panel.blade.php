<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
    
    {{-- 🟢 1. BOTÓN DE RETORNO AL LOBBY (Solo aparece si estás buscando partida) --}}
    @if($activeLobby)
        <a href="{{ route('lobby.show', $activeLobby->id) }}" wire:navigate 
           class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-full shadow-lg border border-green-400 animate-bounce transition-transform transform hover:scale-105">
            
            <div class="relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-200 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </div>
            
            <div class="text-xs font-bold uppercase tracking-wider">
                @if($activeLobby->status === 'searching')
                    Buscando...
                @elseif($activeLobby->status === 'confirming')
                    ⚠️ Confirmar
                @else
                    En Partida
                @endif
            </div>
        </a>
    @endif

    {{-- 🔴 2. PANEL DE AMIGOS (CHAT) --}}
    
    {{-- Ventana desplegable --}}
    @if($isOpen)
        <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-2xl w-80 overflow-hidden mb-2">
            {{-- Header --}}
            <div class="bg-gray-800 p-3 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-white text-sm">Amigos & Party</h3>
                <button wire:click="toggle" class="text-gray-400 hover:text-white">✖</button>
            </div>

            {{-- Buscador --}}
            <div class="p-2">
                <input type="text" wire:model.live="searchQuery" placeholder="Buscar jugadores..." 
                       class="w-full bg-gray-950 text-white text-xs p-2 rounded border border-gray-700 focus:border-blue-500">
            </div>

            {{-- Lista --}}
            <div class="max-h-64 overflow-y-auto p-2 space-y-2">
                
                {{-- Resultados de búsqueda --}}
                @if(count($searchResults) > 0)
                    <p class="text-[10px] text-gray-500 uppercase font-bold">Resultados</p>
                    @foreach($searchResults as $result)
                        <div class="flex justify-between items-center bg-gray-800 p-2 rounded">
                            <span class="text-white text-xs">{{ $result->name }}</span>
                            <button wire:click="addFriend({{ $result->id }})" class="text-blue-400 hover:text-blue-300 text-xs font-bold">+ Agregar</button>
                        </div>
                    @endforeach
                @endif

                {{-- Lista de Amigos --}}
                <p class="text-[10px] text-gray-500 uppercase font-bold mt-2">Mis Amigos</p>
                @forelse($friends as $friend)
                    <div class="flex items-center gap-2 hover:bg-gray-800 p-2 rounded cursor-pointer transition">
                        <div class="relative">
                            <img src="{{ $friend->profile_photo_url }}" class="w-8 h-8 rounded-full">
                            <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-gray-900 rounded-full"></div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200 font-medium">{{ $friend->name }}</p>
                            <p class="text-[10px] text-green-400">Online</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center py-4">No tienes amigos conectados.</p>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Botón Redondo Principal --}}
    <button wire:click="toggle" class="h-14 w-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        {{-- Badge de notificaciones (Opcional) --}}
        {{-- <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 rounded-full border-2 border-gray-900"></span> --}}
    </button>
</div>