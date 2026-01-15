<div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6 text-white relative">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900 to-purple-900 opacity-50 pointer-events-none"></div>
    
    <div class="relative z-10">
        <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Buscador de Partida
        </h3>

        @if (session()->has('error'))
            <div class="bg-red-500 text-white p-2 rounded mb-4 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <div class="flex bg-gray-800 rounded-lg p-1">
                <button 
                    wire:click="setMode('casual')"
                    class="px-4 py-2 rounded-md font-bold shadow transition {{ $mode === 'casual' ? 'bg-green-600 text-white' : 'text-gray-400 hover:text-white' }}">
                    Casual (Pichanga)
                </button>
                <button 
                    wire:click="setMode('ranked')"
                    class="px-4 py-2 rounded-md font-bold shadow transition {{ $mode === 'ranked' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white' }}">
                    Competitivo (Ranked)
                </button>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <select wire:model.live="selectedSport" class="bg-gray-800 border-gray-700 rounded-md text-white focus:ring-green-500 w-full md:w-auto">
                    @foreach($sports as $sport)
                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="selectedDistrict" class="bg-gray-800 border-gray-700 rounded-md text-white focus:ring-green-500 w-full md:w-auto">
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <button 
                wire:click="searchMatch"
                wire:loading.attr="disabled"
                class="relative bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white text-xl font-black py-4 px-12 rounded-full shadow-lg transform hover:scale-105 transition duration-200 flex items-center gap-3 border-2 border-green-300 disabled:opacity-50 disabled:cursor-wait">
                
                <span wire:loading.remove>BUSCAR PARTIDA</span>
                <span wire:loading>BUSCANDO...</span>
                
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-20 top-0 left-0"></span>
            </button>
        </div>
        
        <p class="text-center text-gray-400 text-sm mt-3">
            Tiempo estimado de espera: <span class="text-green-400">48h Max</span>
        </p>
    </div>
</div>