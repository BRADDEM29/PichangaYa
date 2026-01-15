<div class="min-h-screen bg-gray-900 text-white pb-10" wire:poll.3s>
    {{-- BARRA SUPERIOR: DATOS DEL LOBBY --}}
    <div class="bg-gray-800 border-b border-gray-700 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col md:flex-row justify-between items-center">
            
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center text-xl font-bold shadow-lg">
                    ⚽
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-wide text-white uppercase">
                        Sala #{{ $lobby->id }} <span class="text-gray-500 mx-2">|</span> {{ $lobby->sport->name }}
                    </h1>
                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        📍 {{ $lobby->district->name }}
                    </p>
                </div>
            </div>

            {{-- ESTADO CENTRAL --}}
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
                        <div class="text-[10px] text-yellow-600 font-mono mt-1">Tiempo restante: 01:59:00</div>
                    </div>
                @endif
            </div>

            {{-- CONTADOR JUGADORES --}}
            <div class="flex items-center gap-2 bg-gray-900 px-3 py-1 rounded-full border border-gray-700">
                <div class="text-sm font-bold text-gray-300">JUGADORES</div>
                <div class="text-xl font-black {{ $playerCount >= $maxPlayers ? 'text-green-500' : 'text-white' }}">
                    {{ $playerCount }}/{{ $maxPlayers }}
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- COLUMNA IZQUIERDA: EQUIPOS (Ocupa 8 columnas) --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- MENSAJE DE CONFIRMACIÓN --}}
            @if($lobby->status === 'confirming' && !$userSlot->confirmed_at)
                <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-xl p-6 shadow-2xl transform scale-105 border-2 border-yellow-400 animate-pulse text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black text-white mb-2">¡PARTIDA ENCONTRADA!</h2>
                        <p class="text-yellow-100 mb-4">La sala está llena. Confirma tu asistencia para reservar la cancha.</p>
                        <button wire:click="confirmAssistance" class="bg-white text-yellow-900 font-black py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 hover:scale-105 transition duration-200">
                            ✅ ACEPTAR PARTIDA
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">
                                            {{ $slot->user->name }}
                                        </p>
                                        @if($slot->is_captain)
                                            <p class="text-[10px] text-yellow-500 leading-none">👑 Líder</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($slot->confirmed_at)
                                        <span class="text-green-500" title="Confirmado">✅</span>
                                    @else
                                        <div class="w-3 h-3 rounded-full bg-gray-600 animate-pulse" title="Esperando..."></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        {{-- Slots vacíos --}}
                        @for($i = $lobby->slots->where('team_side', 'A')->count(); $i < 7; $i++)
                            <div class="h-14 border-2 border-dashed border-gray-800 rounded-lg flex items-center justify-center text-gray-700 text-xs font-bold">
                                LIBRE
                            </div>
                        @endfor
                    </div>
                </div>

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
                                        <p class="text-sm font-bold {{ $slot->user_id == Auth::id() ? 'text-white' : 'text-gray-400' }}">
                                            {{ $slot->user->name }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @if($slot->confirmed_at)
                                        <span class="text-green-500">✅</span>
                                    @else
                                        <div class="w-3 h-3 rounded-full bg-gray-600 animate-pulse"></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        {{-- Slots vacíos --}}
                        @for($i = $lobby->slots->where('team_side', 'B')->count(); $i < 7; $i++)
                            <div class="h-14 border-2 border-dashed border-gray-800 rounded-lg flex items-center justify-center text-gray-700 text-xs font-bold">
                                LIBRE
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: ENTRETENIMIENTO (Ocupa 4 columnas) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- 1. CARRUSEL DE CANCHAS (Mejorado) --}}
            <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700">
                <h3 class="font-bold text-gray-300 text-sm uppercase tracking-wider mb-4 border-b border-gray-700 pb-2">
                    🏟️ Sedes en {{ $lobby->district->name }}
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($nearbyCanchas as $cancha)
                        <div class="group relative aspect-square rounded-lg overflow-hidden cursor-pointer shadow-lg">
                            @if($cancha->media->first())
                                <img src="{{ Storage::url($cancha->media->first()->file_path) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:rotate-1">
                            @else
                                <div class="w-full h-full bg-gray-700 flex items-center justify-center text-[10px]">Sin Foto</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-2">
                                <span class="text-[10px] font-bold text-white truncate w-full">{{ $cancha->name }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-gray-500 text-xs py-4">No hay canchas registradas aquí.</div>
                    @endforelse
                </div>
            </div>

            {{-- 2. LOBBY HOPPER (Salas recomendadas) --}}
            <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700">
                <h3 class="font-bold text-gray-300 text-sm uppercase tracking-wider mb-4 border-b border-gray-700 pb-2">
                    🔀 Otras Salas Activas
                </h3>
                <div class="space-y-3">
                    @forelse($suggestedLobbies as $suggested)
                        <a href="{{ route('lobby.show', $suggested->id) }}" class="block bg-gray-700/50 hover:bg-gray-700 p-3 rounded-xl border border-gray-600 hover:border-blue-500 transition group relative overflow-hidden">
                            <div class="absolute inset-y-0 left-0 w-1 bg-blue-500 group-hover:bg-blue-400 transition"></div>
                            <div class="flex justify-between items-center pl-2">
                                <div>
                                    <p class="font-bold text-sm text-gray-200">Sala #{{ $suggested->id }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $suggested->district->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-mono font-bold text-blue-300">
                                        {{ 14 - $suggested->slots_count }} cupos
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 border-2 border-dashed border-gray-700 rounded-xl">
                            <p class="text-gray-500 text-xs">No hay otras salas.</p>
                            <p class="text-gray-600 text-[10px]">¡Eres el pionero!</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="text-center">
                <button onclick="window.location.href='{{ route('arena.index') }}'" class="text-xs text-red-400 hover:text-red-300 underline">
                    Salir de la sala
                </button>
            </div>

        </div>
    </div>
</div>