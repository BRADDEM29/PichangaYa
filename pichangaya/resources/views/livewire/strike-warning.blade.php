<div wire:poll.5s="checkStrikes">
    @if($showOverlay)
        {{-- 
            x-data="{ accepted: false }" maneja el estado local.
            Si accepted es false, se muestra el overlay.
            z-[9999] asegura que esté encima de TODO.
        --}}
        <div x-data="{ accepted: false }" 
             x-show="!accepted" 
             style="display: none;" {{-- Evita parpadeo inicial --}}
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[9999] bg-red-900/95 flex items-center justify-center p-4 backdrop-blur-md">
            
            <div class="bg-black border-4 border-red-600 rounded-xl shadow-2xl max-w-2xl w-full p-8 text-center relative overflow-hidden animate-[pulse_3s_infinite]">
                
                {{-- Fondo con icono --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
                    <svg class="w-96 h-96 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22M12 6l-.01 10-1.98.01L12 6zm-1.01 12h2.02v2h-2.02z"/></svg>
                </div>

                <div class="relative z-10">
                    <div class="inline-block bg-red-600 text-white font-black text-xl px-4 py-1 rounded mb-6 uppercase tracking-widest animate-bounce">
                        ⚠️ Última Advertencia
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black text-white mb-6 uppercase leading-tight">
                        ¡Detente Ahí!
                    </h2>

                    <p class="text-xl text-gray-200 mb-8 font-bold">
                        Has acumulado <span class="text-red-500 text-4xl">3 STRIKES</span> consecutivos.
                    </p>

                    <div class="bg-red-950/80 border border-red-500/50 p-6 rounded-lg mb-8 shadow-inner">
                        <p class="text-red-200 text-lg leading-relaxed">
                            Nuestro sistema ha detectado un comportamiento irregular en tus reservas.
                            <br>
                            <span class="text-white font-bold uppercase block mt-4 text-xl bg-red-800/50 p-2 rounded">
                                🛑 Una cancelación más y tu cuenta será BLOQUEADA permanentemente.
                            </span>
                        </p>
                    </div>

                    {{-- El botón "Aceptar" es la única salida --}}
                    <button @click="accepted = true" 
                            class="w-full md:w-auto bg-white hover:bg-gray-200 text-red-900 font-black text-lg py-4 px-10 rounded-full transition transform hover:scale-105 shadow-[0_0_30px_rgba(255,0,0,0.6)] uppercase tracking-wide cursor-pointer ring-4 ring-transparent hover:ring-red-500">
                        Entiendo las consecuencias
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>