@props(['items'])

<div x-data="{ 
        active: 0, 
        total: {{ $items->count() }},
        autoplay: null,
        
        init() {
            this.start();
        },
        start() {
            this.autoplay = setInterval(() => { this.next(); }, 5000);
        },
        stop() {
            if (this.autoplay) {
                clearInterval(this.autoplay);
                this.autoplay = null;
            }
        },
        next() {
            this.active = (this.active === this.total - 1) ? 0 : this.active + 1;
        },
        prev() {
            this.active = (this.active === 0) ? this.total - 1 : this.active - 1;
        }
    }"
    @mouseenter="stop()"
    @mouseleave="start()"
    class="relative w-full h-[500px] bg-gray-900 rounded-2xl shadow-xl overflow-hidden group border border-gray-800">

    {{-- SLIDES --}}
    @foreach($items as $index => $item)
        {{-- 
            CAMBIO CLAVE: Usamos x-show con x-cloak. 
            Quitamos style="display:none" para evitar que se quede blanco si falla JS.
        --}}
        <div class="absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out"
             x-show="active === {{ $index }}"
             x-transition:enter="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="opacity-100"
             x-transition:leave-end="opacity-0">
            
            {{-- 1. ENLACE GLOBAL (z-20) --}}
            <a href="{{ route('canchas.show', $item) }}" class="absolute inset-0 z-20 cursor-pointer"></a>

            {{-- 2. IMAGEN (z-0) --}}
            <div class="absolute inset-0 z-0">
                @if($item->getFirstMediaUrl('canchas'))
                    <img src="{{ $item->getFirstMediaUrl('canchas', 'large') }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $item->name }}">
                @else
                    {{-- Imagen de respaldo de Internet --}}
                    <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" 
                         class="w-full h-full object-cover"
                         alt="Cancha Genérica">
                @endif
            </div>

            {{-- 3. SOMBRA PARA TEXTO (z-10) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent z-10 pointer-events-none"></div>

            {{-- 4. INFO TEXTO (z-30 pero pointer-events-none) --}}
            <div class="absolute bottom-0 left-0 w-full p-8 z-30 pointer-events-none">
                
                {{-- Badge --}}
                @if($item->is_featured)
                    <span class="inline-block bg-yellow-400 text-black text-xs font-black px-3 py-1 rounded mb-2 shadow-sm transform -skew-x-12">
                        ★ DESTACADA
                    </span>
                @else
                    <span class="inline-block bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded mb-2 shadow-sm">
                        RECOMENDADA
                    </span>
                @endif

                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-2 drop-shadow-md leading-tight">
                    {{ $item->name }}
                </h2>
                
                <p class="text-xl text-gray-200 font-medium flex items-center gap-2">
                    📍 {{ $item->district->name ?? 'Cusco' }}
                </p>
            </div>
        </div>
    @endforeach

    {{-- 5. FLECHAS (z-40) --}}
    <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-black/40 text-white hover:bg-black/70 transition backdrop-blur-sm border border-white/20 cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-black/40 text-white hover:bg-black/70 transition backdrop-blur-sm border border-white/20 cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- 6. PUNTOS (z-40) --}}
    <div class="absolute bottom-6 right-6 flex space-x-2 z-40">
        <template x-for="i in total">
            <button @click.stop="active = i - 1; stop(); start();" 
                    class="h-2 rounded-full transition-all duration-300 shadow-sm cursor-pointer border border-white/20"
                    :class="active === i - 1 ? 'w-8 bg-yellow-400' : 'w-2 bg-white/50 hover:bg-white'"></button>
        </template>
    </div>

</div>