@props(['items'])
{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\components\carousel.blade.php --}}

<div x-data="{ 
        active: 0, 
        total: {{ $items->count() }},
        autoplay: null,
        isHovering: false, // 🟢 NUEVO: Estado estricto del mouse
        
        init() {
            this.start();
        },
        start() {
            // 🟢 LÓGICA CORREGIDA: Si el mouse está encima, NO iniciamos el timer
            if (this.isHovering) return;

            // Limpiamos intervalo previo por seguridad
            if(this.autoplay) clearInterval(this.autoplay);
            
            this.autoplay = setInterval(() => { 
                this.next(true); // true indica que es cambio automático
            }, 5000);
        },
        stop() {
            if (this.autoplay) {
                clearInterval(this.autoplay);
                this.autoplay = null;
            }
        },
        // 🟢 Funciones dedicadas para el mouse
        handleMouseEnter() {
            this.isHovering = true;
            this.stop();
        },
        handleMouseLeave() {
            this.isHovering = false;
            this.start();
        },
        next(isAuto = false) {
            this.active = (this.active === this.total - 1) ? 0 : this.active + 1;
            
            // Si fue un clic manual, reiniciamos el ciclo PERO respetando el hover
            if (!isAuto) {
                this.stop();
                this.start(); // start() verificará isHovering y no arrancará si el mouse sigue ahí
            }
        },
        prev() {
            this.active = (this.active === 0) ? this.total - 1 : this.active - 1;
            this.stop();
            this.start();
        }
    }"
    {{-- EVENTOS MOUSE PADRE --}}
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave()"
    class="relative w-full h-[500px] bg-gray-900 rounded-2xl shadow-xl overflow-hidden group border border-gray-800">

    {{-- SLIDES --}}
    @foreach($items as $index => $item)
        <div class="absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out"
             x-show="active === {{ $index }}"
             x-cloak
             x-transition:enter="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="opacity-100"
             x-transition:leave-end="opacity-0">
            
            {{-- 1. ENLACE GLOBAL (z-20) --}}
            <a href="{{ route('canchas.show', $item) }}" class="absolute inset-0 z-20 cursor-pointer"></a>

            {{-- 2. IMAGEN (z-0) --}}
            <div class="absolute inset-0 z-0">
                @if($item->getFirstMediaUrl('canchas'))
                    {{-- 🟢 CAMBIO APLICADO: Sin 'large', usa el original WebP optimizado --}}
                    <img src="{{ $item->getFirstMediaUrl('canchas') }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $item->name }}">
                @else
                    {{-- Placeholder --}}
                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            {{-- 3. SOMBRA DEGRADADA (z-10) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent z-10 pointer-events-none"></div>

            {{-- 4. INFO TEXTO (z-30) --}}
            <div class="absolute bottom-0 left-0 w-full p-8 z-30 pointer-events-none">
                
                @if($item->is_featured)
                    <span class="inline-flex items-center bg-yellow-400 text-black text-xs font-black px-3 py-1 rounded mb-2 shadow-sm transform -skew-x-12 uppercase tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Destacada
                    </span>
                @else
                    <span class="inline-flex items-center bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded mb-2 shadow-sm uppercase tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Recomendada
                    </span>
                @endif

                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-2 drop-shadow-md leading-tight">
                    {{ $item->name }}
                </h2>
                
                <p class="text-lg md:text-xl text-gray-200 font-medium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    {{ $item->district->name ?? 'Cusco' }}
                </p>
            </div>
        </div>
    @endforeach

    {{-- 5. FLECHAS DE NAVEGACIÓN (z-40) --}}
    {{-- 🟢 Simplificamos los clicks, la lógica está en JS --}}
    <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-black/30 text-white hover:bg-black/60 transition backdrop-blur-sm border border-white/10 cursor-pointer group-hover:bg-black/50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    
    <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-black/30 text-white hover:bg-black/60 transition backdrop-blur-sm border border-white/10 cursor-pointer group-hover:bg-black/50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- 6. PUNTOS INDICADORES (z-40) --}}
    <div class="absolute bottom-6 right-6 flex space-x-2 z-40">
        <template x-for="i in total">
            <button @click.stop="active = i - 1; stop(); start();" 
                    class="h-2 rounded-full transition-all duration-300 shadow-sm cursor-pointer border border-white/20 backdrop-blur-sm"
                    :class="active === i - 1 ? 'w-8 bg-yellow-400' : 'w-2 bg-white/50 hover:bg-white'">
            </button>
        </template>
    </div>

</div>