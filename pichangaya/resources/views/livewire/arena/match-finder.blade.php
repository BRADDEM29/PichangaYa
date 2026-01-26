{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\arena\match-finder.blade.php --}}

<section class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6 text-white relative group" aria-label="Buscador de Partidas">
    
    {{-- 🟢 FONDO DE IMAGEN: Combinamos un filtro oscuro con la imagen para legibilidad --}}
    <div class="absolute inset-0 transition-transform duration-1000 group-hover:scale-105" 
         style="background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(88, 28, 135, 0.6)), url('{{ asset('images/Firefly-_1_.webp') }}'); background-size: cover; background-position: center;" 
         aria-hidden="true">
    </div>
    
    {{-- Contenido (z-10 para estar encima de la imagen) --}}
    <div class="relative z-10">
        
        {{-- HEADER --}}
        <header>
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 drop-shadow-md">
                <svg class="w-8 h-8 text-green-400 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Buscador de Partida
            </h2>
        </header>

        {{-- Alertas --}}
        @if (session()->has('error'))
            <div class="bg-red-500/90 backdrop-blur-sm text-white p-2 rounded mb-4 text-sm font-bold border border-red-400" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM --}}
        <form wire:submit.prevent="searchMatch">
            
            <fieldset class="flex flex-col md:flex-row gap-6 mb-6">
                <legend class="sr-only">Configuración de búsqueda</legend>

                {{-- GRUPO: Modo de Juego --}}
                <div class="flex bg-gray-900/60 backdrop-blur-sm rounded-lg p-1 border border-gray-600" role="group" aria-label="Modo de juego">
                    <button type="button" wire:click="setMode('casual')" 
                        class="px-4 py-2 rounded-md font-bold shadow transition {{ $mode === 'casual' ? 'bg-green-600 text-white' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        Casual (Pichanga)
                    </button>
                    <button type="button" wire:click="setMode('ranked')" 
                        class="px-4 py-2 rounded-md font-bold shadow transition {{ $mode === 'ranked' ? 'bg-red-600 text-white' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        Competitivo (Ranked)
                    </button>
                </div>

                {{-- GRUPO: Selectores --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <label for="sport-select" class="sr-only">Deporte</label>
                    <select id="sport-select" wire:model.live="selectedSport" 
                            class="bg-gray-900/60 backdrop-blur-sm border-gray-600 rounded-md text-white focus:ring-green-500 w-full md:w-auto cursor-pointer hover:bg-gray-800/80">
                        @foreach($sports as $sport)
                            <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                        @endforeach
                    </select>

                    <label for="district-select" class="sr-only">Distrito</label>
                    <select id="district-select" wire:model.live="selectedDistrict" 
                            class="bg-gray-900/60 backdrop-blur-sm border-gray-600 rounded-md text-white focus:ring-green-500 w-full md:w-auto cursor-pointer hover:bg-gray-800/80">
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>

            {{-- BOTÓN ACCIÓN --}}
            <div class="flex justify-center mt-8">
                <button type="submit" wire:loading.attr="disabled"
                    class="relative bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white text-xl font-black py-4 px-12 rounded-full shadow-[0_0_15px_rgba(34,197,94,0.5)] transform hover:scale-105 transition duration-200 flex items-center gap-3 border border-green-400 disabled:opacity-50 disabled:cursor-wait">
                    
                    <span wire:loading.remove>BUSCAR PARTIDA</span>
                    <span wire:loading>BUSCANDO...</span>
                    
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-20 top-0 left-0"></span>
                </button>
            </div>

        </form>
        
        {{-- FOOTER --}}
        <footer class="mt-3">
            <p class="text-center text-gray-300 text-sm font-medium drop-shadow-sm">
                Tiempo estimado de espera: <span class="text-green-400 font-bold">48h Max</span>
            </p>
        </footer>

    </div>
</section>