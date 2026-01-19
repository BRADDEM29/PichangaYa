<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-white leading-tight">
                {{ $tournament->name }} <span class="text-sm font-normal text-gray-500">| Bracket Oficial</span>
            </h2>
            @if($tournament->status == 'finished')
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">🏆 FINALIZADO</span>
            @else
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">🟢 EN CURSO</span>
            @endif
        </div>
    </x-slot>

    <div class="py-8 overflow-x-auto"> {{-- Scroll horizontal en móvil --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-w-[800px]"> {{-- Min width para mantener forma del bracket --}}
            
            {{-- GRID DE 3 COLUMNAS: CUARTOS | SEMIS | FINAL --}}
            <div class="grid grid-cols-3 gap-8">
                
                {{-- COLUMNA 1: CUARTOS DE FINAL (4 Partidos) --}}
                <div class="space-y-8 flex flex-col justify-around">
                    <h3 class="text-center font-bold text-gray-400 uppercase text-xs tracking-widest mb-2">Cuartos de Final</h3>
                    @foreach($matches->where('phase', 'quarter_final') as $match)
                        <x-match-card :match="$match" />
                    @endforeach
                </div>

                {{-- COLUMNA 2: SEMIFINALES (2 Partidos) --}}
                <div class="space-y-16 flex flex-col justify-around py-12">
                    <h3 class="text-center font-bold text-gray-400 uppercase text-xs tracking-widest mb-2">Semifinales</h3>
                    @foreach($matches->where('phase', 'semi_final') as $match)
                        <x-match-card :match="$match" />
                    @endforeach
                </div>

                {{-- COLUMNA 3: GRAN FINAL (1 Partido) --}}
                <div class="flex flex-col justify-center py-24">
                    <h3 class="text-center font-bold text-yellow-500 uppercase text-xs tracking-widest mb-4">🏆 GRAN FINAL</h3>
                    @foreach($matches->where('phase', 'final') as $match)
                        <x-match-card :match="$match" :isFinal="true" />
                        
                        @if($match->winner)
                            <div class="mt-8 text-center animate-bounce">
                                <p class="text-gray-500 text-sm">Campeón</p>
                                <h1 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $match->winner->team_name }}</h1>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>