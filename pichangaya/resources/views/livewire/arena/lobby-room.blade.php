{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\arena\lobby-room.blade.php --}}

<div class="fixed inset-0 z-50 bg-gray-900 text-white overflow-y-auto" wire:poll.4s>
    
    {{-- Navegación --}}
    <div class="relative z-50 bg-gray-900">
        <livewire:navigation-menu />
    </div>

    {{-- BARRA SUPERIOR --}}
    <div class="bg-gray-800 border-b border-gray-700 shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col md:flex-row justify-between items-center">
            
            <div class="flex items-center gap-4">
                {{-- Botón VOLVER (Minimizar) --}}
                <a href="{{ route('arena.index') }}" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-full text-gray-300 transition" title="Volver al menú">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>

                <div class="h-10 w-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center text-xl font-bold shadow-lg">⚽</div>
                <div>
                    <h1 class="text-lg font-black tracking-wide text-white uppercase">
                        Sala #{{ $lobby->id }} <span class="text-gray-500 mx-2">|</span> {{ $lobby->sport->name }}
                    </h1>
                </div>
            </div>

            {{-- Estado --}}
            <div class="my-2 md:my-0">
                @if($lobby->status === 'searching')
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-bold text-blue-400 animate-pulse uppercase tracking-widest">Buscando Jugadores...</span>
                        <div class="text-[10px] text-gray-500 font-mono bg-gray-900 px-2 py-0.5 rounded mt-1">
                            EXPIRA EN: {{ \Carbon\Carbon::parse($lobby->expires_at)->diffForHumans() }}
                        </div>
                    </div>
                @elseif($lobby->status === 'confirming')
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-bold text-yellow-400 animate-bounce uppercase tracking-widest">⚠️ Confirmando Partida</span>
                    </div>
                @endif
            </div>

            {{-- Contador --}}
            <div class="flex items-center gap-2 bg-gray-900 px-3 py-1 rounded-full border border-gray-700">
                <div class="text-sm font-bold text-gray-300">JUGADORES</div>
                <div class="text-xl font-black {{ $playerCount >= $maxPlayers ? 'text-green-500' : 'text-white' }}">
                    {{ $playerCount }}/{{ $maxPlayers }}
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENIDO DEL LOBBY --}}
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- COLUMNA EQUIPOS (8) --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- EQUIPO A --}}
                <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border border-gray-700/50">
                    <div class="bg-gray-700/50 p-4 border-b border-gray-700 flex justify-between items-center">
                        <span class="font-bold text-blue-400">EQUIPO A</span>
                        <span class="text-xs bg-black/40 px-2 py-1 rounded text-gray-400">{{ $lobby->slots->where('team_side', 'A')->count() }}/7</span>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($lobby->slots->where('team_side', 'A') as $slot)
                            <div class="flex items-center justify-between bg-gray-900/40 p-2 rounded-lg border border-gray-700/30">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $slot->user->profile_photo_url }}" class="w-10 h-10 rounded-full border-2 {{ $slot->user_id == Auth::id() ? 'border-blue-500' : 'border-gray-600' }}">
                                    <div>
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">{{ $slot->user->name }}</p>
                                        @if($slot->is_captain) <p class="text-[10px] text-yellow-500 leading-none font-bold">👑 Líder</p> @endif
                                    </div>
                                </div>
                                
                                {{-- 🟢 BOTONES DE CONTROL (SOLO PARA MI USUARIO) --}}
                                @if($slot->user_id === Auth::id())
                                    <div class="flex items-center gap-2">
                                        {{-- Botón Ser Líder --}}
                                        <button wire:click="toggleCaptain" 
                                                class="p-1.5 rounded-lg transition {{ $slot->is_captain ? 'bg-yellow-500/20 text-yellow-500 hover:bg-yellow-500/40' : 'bg-gray-700 text-gray-400 hover:bg-gray-600 hover:text-white' }}"
                                                title="Liderazgo">
                                            @if($slot->is_captain)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </button>
                                        {{-- Botón Cambiar Equipo --}}
                                        <button wire:click="switchTeam" class="p-1.5 bg-gray-700 text-blue-400 hover:bg-blue-600 hover:text-white rounded-lg transition" title="Cambiar a Equipo B">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <div>@if($slot->confirmed_at) ✅ @else <div class="w-2 h-2 rounded-full bg-gray-500"></div> @endif</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- EQUIPO B --}}
                <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border border-gray-700/50">
                    <div class="bg-gray-700/50 p-4 border-b border-gray-700 flex justify-between items-center">
                        <span class="font-bold text-red-400">EQUIPO B</span>
                        <span class="text-xs bg-black/40 px-2 py-1 rounded text-gray-400">{{ $lobby->slots->where('team_side', 'B')->count() }}/7</span>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($lobby->slots->where('team_side', 'B') as $slot)
                             <div class="flex items-center justify-between bg-gray-900/40 p-2 rounded-lg border border-gray-700/30">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $slot->user->profile_photo_url }}" class="w-10 h-10 rounded-full border-2 {{ $slot->user_id == Auth::id() ? 'border-red-500' : 'border-gray-600' }}">
                                    <div>
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">{{ $slot->user->name }}</p>
                                        @if($slot->is_captain) <p class="text-[10px] text-yellow-500 leading-none font-bold">👑 Líder</p> @endif
                                    </div>
                                </div>

                                {{-- 🟢 BOTONES DE CONTROL (SOLO PARA MI USUARIO) --}}
                                @if($slot->user_id === Auth::id())
                                    <div class="flex items-center gap-2">
                                        {{-- Botón Ser Líder --}}
                                        <button wire:click="toggleCaptain" 
                                                class="p-1.5 rounded-lg transition {{ $slot->is_captain ? 'bg-yellow-500/20 text-yellow-500 hover:bg-yellow-500/40' : 'bg-gray-700 text-gray-400 hover:bg-gray-600 hover:text-white' }}"
                                                title="Liderazgo">
                                            @if($slot->is_captain)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </button>
                                        {{-- Botón Cambiar Equipo --}}
                                        <button wire:click="switchTeam" class="p-1.5 bg-gray-700 text-red-400 hover:bg-red-600 hover:text-white rounded-lg transition transform rotate-180" title="Cambiar a Equipo A">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <div>@if($slot->confirmed_at) ✅ @else <div class="w-2 h-2 rounded-full bg-gray-500"></div> @endif</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA (4) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- CARRUSEL --}}
            <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-lg border border-gray-700">
                <h3 class="font-bold text-gray-300 text-sm uppercase tracking-wider p-4 border-b border-gray-700 bg-gray-900/50">🏟️ Sedes Recomendadas</h3>
                @if($carouselItems && $carouselItems->count() > 0)
                    <div class="aspect-video relative">
                        <x-carousel :items="$carouselItems" />
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 text-sm">No se encontraron canchas.</div>
                @endif
            </div>

            {{-- 💬 CHAT DE LA SALA --}}
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden flex flex-col h-80 shadow-lg"
                 x-data="{ 
                    scroll() { this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight } 
                 }"
                 x-init="scroll()"
                 @scroll-lobby-chat.window="setTimeout(() => scroll(), 100)"
            >
                {{-- Cabecera Chat --}}
                <div class="bg-gray-900/50 p-3 border-b border-gray-700 flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">💬 Chat de Sala</span>
                    <span class="text-[9px] bg-blue-900 text-blue-200 px-2 py-0.5 rounded">General</span>
                </div>

                {{-- Mensajes --}}
                <div x-ref="chatBox" class="flex-1 overflow-y-auto p-3 space-y-2 bg-gray-900/30">
                    @forelse($lobbyMessages as $msg)
                        <div class="flex flex-col">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xs font-bold {{ $msg->sender_id === auth()->id() ? 'text-green-400' : 'text-blue-400' }}">
                                    {{ $msg->sender->name }}:
                                </span>
                                <span class="text-xs text-gray-300 break-words">{{ $msg->content }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 opacity-50">
                            <p class="text-[10px] text-gray-500">La sala está en silencio...</p>
                        </div>
                    @endforelse
                </div>

                {{-- Input --}}
                <form wire:submit.prevent="sendMessage" class="p-2 bg-gray-800 border-t border-gray-700">
                    <div class="flex gap-2">
                        <input type="text" wire:model="newMessage" placeholder="Escribe a todos..." 
                               class="flex-1 bg-gray-900 border-gray-600 rounded text-xs text-white focus:ring-1 focus:ring-blue-500 px-3 py-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- BOTÓN SALIR --}}
            <div class="text-center pt-4">
                <button wire:click="exitLobby" 
                        wire:confirm="¿Seguro que quieres abandonar la sala?"
                        class="text-sm text-red-400 hover:text-red-300 underline font-bold cursor-pointer">
                    ❌ SALIR DE LA SALA DEFINITIVAMENTE
                </button>
            </div>
        </div>
    </div>
</div>