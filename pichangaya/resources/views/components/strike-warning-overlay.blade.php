@if(session('warning_strike_level') == 3)
    <div x-data="{ show: true }" 
         x-show="show" 
         class="fixed inset-0 z-[100] bg-red-900/95 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-500">
        
        <div class="bg-black border-4 border-red-600 rounded-xl shadow-2xl max-w-2xl w-full p-8 text-center relative overflow-hidden animate-pulse-slow">
            
            {{-- Fondo con icono de advertencia gigante --}}
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
                    Has cancelado <span class="text-red-500 text-3xl">3 reservas</span> consecutivas.
                </p>

                <div class="bg-red-950/50 border border-red-500/50 p-6 rounded-lg mb-8">
                    <p class="text-red-200 text-lg leading-relaxed">
                        Nuestro sistema ha detectado un comportamiento inusual. 
                        <br>
                        <span class="text-white font-bold uppercase block mt-2 text-xl">
                            Si cancelas o no pagas una reserva más, tu cuenta será BLOQUEADA PERMANENTEMENTE.
                        </span>
                    </p>
                </div>

                <button @click="show = false" 
                        class="w-full md:w-auto bg-white text-red-900 font-black text-lg py-4 px-10 rounded-full hover:bg-gray-200 hover:scale-105 transition transform shadow-[0_0_20px_rgba(255,0,0,0.5)] uppercase tracking-wide">
                    Entiendo las consecuencias
                </button>
            </div>
        </div>
    </div>

    <style>
        .animate-pulse-slow {
            animation: pulse-border 2s infinite;
        }
        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }
    </style>
@endif