<section class="relative w-full max-w-5xl mx-auto mt-8 perspective-1000 group" aria-label="Panel de Búsqueda">
    
    {{-- TARJETA PRINCIPAL --}}
    <article class="relative bg-gray-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-800 transition-all duration-500 hover:border-gray-700">
        
        {{-- FONDO DECORATIVO --}}
        <figure class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/Firefly-_1_.webp') }}" 
                 class="w-full h-full object-cover opacity-40 filter blur-sm transition-transform duration-1000 group-hover:scale-105" 
                 alt="Fondo Arena">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-gray-900/80 to-blue-900/40"></div>
        </figure>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="relative z-10 p-8 md:p-10">
            
            {{-- ENCABEZADO --}}
            <header class="mb-8 text-center md:text-left">
                <hgroup>
                    <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-2 flex flex-col md:flex-row items-center gap-3 drop-shadow-lg">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-500">
                            Buscador Dota 2
                        </span>
                        
                        {{-- Indicador de Estado --}}
                        <figure class="relative flex h-6 w-6">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-6 w-6 bg-green-500"></span>
                        </figure>
                    </h2>
                    <p class="text-gray-400 text-sm font-medium tracking-wide">
                        Sistema de Matchmaking Persistente • <span class="text-green-400 font-bold">Activo</span>
                    </p>
                </hgroup>
            </header>

            {{-- MENSAJES DE ERROR --}}
            @if (session()->has('error'))
                <aside class="bg-red-900/50 backdrop-blur-md text-red-200 p-4 rounded-xl mb-6 text-sm font-bold border border-red-500/50 flex items-center gap-3 animate-pulse" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </aside>
            @endif

            {{-- FORMULARIO DE BÚSQUEDA --}}
            <form wire:submit.prevent="searchMatch">
                
                <fieldset class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 border-none p-0 m-0">
                    <legend class="sr-only">Configuración de búsqueda</legend>

                    {{-- 1. SWITCH DE MODO (Casual / Torneo) --}}
                    <section class="lg:col-span-4 bg-gray-800/60 backdrop-blur-md rounded-2xl p-1.5 border border-gray-700 flex flex-col sm:flex-row gap-1" role="group" aria-label="Modo de juego">
                        
                        {{-- Botón Casual --}}
                        <button type="button" wire:click="setMode('casual')" 
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ $mode === 'casual' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg border border-green-500/50' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <span class="w-2 h-2 rounded-full {{ $mode === 'casual' ? 'bg-white' : 'bg-gray-600' }}"></span>
                            Casual
                        </button>
                        
                        {{-- Botón Torneo (Ranked) --}}
                        <button type="button" wire:click="setMode('ranked')" 
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ $mode === 'ranked' ? 'bg-gradient-to-r from-red-600 to-pink-600 text-white shadow-lg border border-red-500/50' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <span class="w-2 h-2 rounded-full {{ $mode === 'ranked' ? 'bg-white' : 'bg-gray-600' }}"></span>
                            Torneo
                        </button>
                    </section>

                    {{-- 2. SELECTORES (Deporte y Ubicación) --}}
                    <section class="lg:col-span-8 flex flex-col sm:flex-row gap-4">
                        
                        {{-- Deporte --}}
                        <label class="relative flex-1 group cursor-pointer block">
                            <span class="sr-only">Deporte</span>
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                            </span>
                            
                            <select wire:model.live="selectedSport" 
                                    class="w-full pl-12 pr-10 py-4 bg-gray-800/60 backdrop-blur-md border border-gray-700 rounded-2xl text-white font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-gray-700/60 appearance-none cursor-pointer">
                                {{-- OPCIONES LIMPIAS (Sin emojis) --}}
                                <option value="1">Fútbol 5 (10 Jugadores)</option>
                                <option value="2">Fútbol 7 (14 Jugadores)</option>
                                <option value="3">Fútbol 11 (22 Jugadores)</option>
                                <option value="6" class="bg-gray-700 font-bold text-yellow-400">Tenis (1 vs 1 - Test)</option> {{-- Aquí está tu prueba --}}
                                <option value="4">Vóley</option>
                                <option value="5">Básquet</option>
                                <option value="7">Futsal</option>
                                <option value="8">Frontón</option>
                                <option value="9">Ping Pong</option>
                                <option value="10">Rugby</option>
                                <option value="11">Béisbol</option>
                            </select>
                            
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </label>

                        {{-- Distrito --}}
                        <label class="relative flex-1 group cursor-pointer block">
                            <span class="sr-only">Distrito</span>
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </span>
                            
                            <select wire:model.live="selectedDistrict" 
                                    class="w-full pl-12 pr-10 py-4 bg-gray-800/60 backdrop-blur-md border border-gray-700 rounded-2xl text-white font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-gray-700/60 appearance-none cursor-pointer">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </label>

                    </section>
                </fieldset>

                {{-- PIE DE FORMULARIO (Botón Buscar) --}}
                <footer class="flex justify-center mt-8 pt-6 border-t border-gray-800">
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full md:w-auto relative group overflow-hidden rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-lg py-4 px-16 shadow-[0_0_20px_rgba(59,130,246,0.4)] transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-wait">
                        
                        <span class="relative z-10 flex items-center justify-center gap-3">
                            <span wire:loading.remove>BUSCAR PARTIDA</span>
                            <span wire:loading>INICIANDO LOBBY...</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        
                        <span class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-white/20 to-transparent z-0"></span>
                    </button>
                </footer>

                <p class="text-center text-gray-500 text-xs font-medium mt-4">
                    Tiempo de espera estimado: <span class="text-blue-400 font-bold">48h Max</span>
                </p>

            </form>
        </main>
    </article>
</section>