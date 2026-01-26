{{-- resources/views/components/arena/match-accept-modal.blade.php --}}

@props(['isOpen', 'userSlot'])

@if($isOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4"
         x-data
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100">
        
        {{-- Fondo Oscuro Borroso --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

        {{-- CAJA DEL MODAL --}}
        <div class="relative bg-black border-2 border-green-500 w-full max-w-md p-8 rounded-xl text-center shadow-[0_0_50px_rgba(34,197,94,0.6)] animate-pulse-slow">
            
            {{-- Decoración Superior --}}
            <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-black border border-green-500 px-6 py-2 rounded-full shadow-[0_0_15px_rgba(34,197,94,0.8)]">
                <span class="text-green-400 font-black uppercase tracking-[0.2em] text-sm animate-pulse">¡Partida Encontrada!</span>
            </div>

            <div class="mt-6 space-y-6">
                
                {{-- Icono Principal Animado --}}
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-green-500 blur-xl opacity-20 rounded-full animate-ping"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="relative z-10 drop-shadow-[0_0_10px_rgba(74,222,128,1)]">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                            <path d="M12 7v10" />
                        </svg>
                    </div>
                </div>

                <p class="text-gray-300 text-sm font-medium">
                    Todos los jugadores deben aceptar para iniciar.
                </p>

                {{-- ACCIONES --}}
                <div class="flex flex-col gap-4">
                    
                    {{-- BOTÓN ACEPTAR --}}
                    @if($userSlot->confirmed_at)
                        {{-- Estado: YA ACEPTÓ --}}
                        <div class="w-full py-4 bg-green-900/30 border border-green-500/50 text-green-400 font-black rounded text-lg flex items-center justify-center gap-3 uppercase tracking-widest cursor-default">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            Esperando al resto...
                        </div>
                    @else
                        {{-- Estado: PENDIENTE --}}
                        <button wire:click="toggleReady" 
                                class="group relative w-full py-5 bg-green-600 hover:bg-green-500 text-white font-black text-xl rounded shadow-[0_0_20px_rgba(34,197,94,0.4)] hover:shadow-[0_0_40px_rgba(34,197,94,0.8)] transition-all transform hover:scale-105 uppercase tracking-widest flex items-center justify-center gap-3 overflow-hidden">
                            
                            {{-- Brillo interno --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shine"></div>
                            
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            ACEPTAR
                        </button>
                    @endif

                    {{-- BOTÓN RECHAZAR --}}
                    <button wire:click="declineMatch"
                            wire:confirm="¿Rechazar la partida te sacará de la sala. Estás seguro?"
                            class="text-red-500 hover:text-white hover:bg-red-600/20 py-2 px-4 rounded transition text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                        RECHAZAR PARTIDA
                    </button>

                </div>
            </div>
        </div>
    </div>

    {{-- CSS para animaciones específicas de este modal --}}
    <style>
        @keyframes shine {
            100% { transform: translateX(100%); }
        }
        .group-hover\:animate-shine:hover {
            animation: shine 0.5s;
        }
    </style>
@endif