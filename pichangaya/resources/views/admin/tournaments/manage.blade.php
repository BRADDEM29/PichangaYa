<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center gap-3">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300">
                {{-- SVG de Engranaje (Settings) --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Administrar Torneo: {{ $tournament->name }}
            </h2>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <section class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                
                {{-- Navegación --}}
                <nav class="mb-8 flex justify-between items-center pb-6 border-b border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.tournaments.index') }}" class="group inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver
                    </a>
                    <a href="{{ route('arena.show', $tournament->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2 rounded-lg shadow-sm transition-all">
                        <span>Ver Bracket Público</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </nav>

                {{-- Lista de Partidos por Ronda --}}
                <div class="space-y-10">
                    @foreach($rounds as $roundNumber => $matches)
                        <section aria-labelledby="round-header-{{ $roundNumber }}">
                            <header class="flex items-center gap-3 mb-5">
                                <span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 text-xs font-black px-2 py-1 rounded uppercase tracking-widest">
                                    @if($loop->last) FINAL @else RONDA {{ $roundNumber }} @endif
                                </span>
                                <div class="h-px bg-gray-200 dark:bg-gray-700 flex-1"></div>
                            </header>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($matches as $match)
                                    @php
                                        $isBye = ($match->team1_id && !$match->team2_id) || (!$match->team1_id && $match->team2_id);
                                    @endphp

                                    <article class="relative border rounded-xl overflow-hidden transition-all duration-300 
                                        {{ $match->winner_id ? 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-800' : 'bg-white border-gray-200 shadow-sm hover:shadow-md dark:bg-gray-800 dark:border-gray-700' }}">
                                        
                                        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Match #{{ $match->match_number }}</span>
                                            @if($match->winner_id)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-green-500">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="p-4">
                                            @if($isBye)
                                                <div class="flex flex-col items-center justify-center py-4 opacity-50">
                                                    <span class="text-sm font-semibold text-gray-500 italic">Pase Directo (Bye)</span>
                                                </div>
                                            @elseif(!$match->team1_id || !$match->team2_id)
                                                <div class="flex flex-col items-center justify-center py-4">
                                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest animate-pulse">Esperando rivales...</span>
                                                </div>
                                            @else
                                                {{-- FORMULARIO PARA ACTUALIZAR --}}
                                                <form action="{{ route('admin.matches.update', $match->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    {{-- Equipo 1 --}}
                                                    <div class="flex items-center justify-between mb-3 {{ $match->winner_id == $match->team1_id ? 'opacity-100' : ($match->winner_id ? 'opacity-40' : '') }}">
                                                        <div class="flex items-center gap-2 overflow-hidden">
                                                            @if($match->winner_id == $match->team1_id)
                                                                <span class="text-green-500">👑</span>
                                                            @endif
                                                            <span class="text-sm font-bold truncate text-gray-700 dark:text-gray-200 {{ $match->winner_id == $match->team1_id ? 'text-green-700 dark:text-green-400' : '' }}">
                                                                {{ $match->team1->team_name }}
                                                            </span>
                                                        </div>
                                                        <input type="number" name="score1" value="{{ $match->score1 }}" min="0" 
                                                            class="w-14 h-8 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-lg text-sm font-mono font-bold focus:ring-indigo-500 focus:border-indigo-500" 
                                                            {{ $match->winner_id ? 'disabled' : '' }}>
                                                    </div>

                                                    {{-- Equipo 2 --}}
                                                    <div class="flex items-center justify-between mb-4 {{ $match->winner_id == $match->team2_id ? 'opacity-100' : ($match->winner_id ? 'opacity-40' : '') }}">
                                                        <div class="flex items-center gap-2 overflow-hidden">
                                                            @if($match->winner_id == $match->team2_id)
                                                                <span class="text-green-500">👑</span>
                                                            @endif
                                                            <span class="text-sm font-bold truncate text-gray-700 dark:text-gray-200 {{ $match->winner_id == $match->team2_id ? 'text-green-700 dark:text-green-400' : '' }}">
                                                                {{ $match->team2->team_name }}
                                                            </span>
                                                        </div>
                                                        <input type="number" name="score2" value="{{ $match->score2 }}" min="0" 
                                                            class="w-14 h-8 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-lg text-sm font-mono font-bold focus:ring-indigo-500 focus:border-indigo-500" 
                                                            {{ $match->winner_id ? 'disabled' : '' }}>
                                                    </div>

                                                    @if(!$match->winner_id)
                                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-xs uppercase font-bold tracking-wider transition-colors shadow-sm">
                                                            Guardar Resultado
                                                        </button>
                                                    @else
                                                        <div class="w-full bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 py-1.5 rounded-lg text-[10px] uppercase font-bold text-center border border-green-100 dark:border-green-800">
                                                            Finalizado
                                                        </div>
                                                    @endif
                                                </form>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

            </section>
        </div>
    </main>
</x-app-layout>