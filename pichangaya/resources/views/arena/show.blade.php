<x-app-layout>
    {{-- 
       ESTILOS CRÍTICOS (Sin cambios, funcionan con clases)
    --}}
    <style>
        .bracket-wrapper {
            display: flex;
            padding: 40px;
            overflow-x: auto;
        }
        
        .round-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 260px;
            flex-shrink: 0;
        }

        .connector-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 50px;
            flex-shrink: 0;
        }

        .connector-block {
            flex-grow: 1;
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .bracket-line {
            position: absolute;
            right: 0;
            top: 25%;
            bottom: 25%;
            width: 100%;
            border-right: 2px solid #6366f1;
            border-top: 2px solid #6366f1;
            border-bottom: 2px solid #6366f1;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .connector-bridge {
            position: absolute;
            right: -25px;
            width: 25px;
            height: 2px;
            background-color: #6366f1;
        }

        .match-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }
        .match-card:hover { transform: scale(1.02); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2); border-color: #818cf8; }
        .dark .match-card { background: #1f2937; border-color: #374151; color: white; }

        .match-card.is-bye {
            opacity: 0.5;
            border-style: dashed;
            background: transparent;
            box-shadow: none;
        }
    </style>

    <x-slot name="header">
        {{-- HEADER: Encabezado semántico --}}
        <header class="flex flex-col md:flex-row justify-between items-center py-2">
            <hgroup>
                <h2 class="font-black text-3xl text-gray-800 dark:text-white uppercase tracking-tighter">
                    {{ $tournament->name }}
                </h2>
                @if($tournament->cancha)
                    <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-semibold">{{ $tournament->cancha->name }}</span>
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">
                            {{ $tournament->cancha->district->name ?? 'Distrito' }}
                        </span>
                    </div>
                @endif
            </hgroup>
            
            <div>
                @if($tournament->status == 'active')
                    <span class="px-4 py-1 bg-green-100 text-green-700 border border-green-300 rounded-full text-xs font-bold uppercase tracking-widest animate-pulse">
                        ● En Curso
                    </span>
                @else
                    <span class="px-4 py-1 bg-gray-800 text-white rounded-full text-xs font-bold uppercase tracking-widest">
                        Finalizado
                    </span>
                @endif
            </div>
        </header>
    </x-slot>

    {{-- MAIN: Contenido principal --}}
    <main class="bg-gray-100 dark:bg-gray-900 min-h-screen overflow-hidden">
        
        {{-- SECTION: Contenedor del Bracket --}}
        <section class="bracket-wrapper" id="bracket-container" aria-label="Diagrama del Torneo">
            
            @foreach($rounds as $roundIndex => $matches)
                
                {{-- SECTION: Columna de Ronda --}}
                <section class="round-column" aria-label="Ronda {{ $loop->iteration }}">
                    
                    {{-- HEADER: Título de la Ronda --}}
                    <header class="text-center mb-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            @if($loop->last) 👑 GRAN FINAL
                            @elseif($loop->iteration == $loop->count - 1) SEMIFINAL
                            @else RONDA {{ $loop->iteration }}
                            @endif
                        </h3>
                    </header>

                    @foreach($matches as $match)
                        <div class="py-3 px-1 w-full"> {{-- Wrapper de layout para espaciado --}}
                            
                            @php
                                $isBye = ($match->team1_id && !$match->team2_id) || (!$match->team1_id && $match->team2_id);
                            @endphp

                            {{-- ARTICLE: Tarjeta del Partido Independiente --}}
                            <article class="match-card {{ $isBye ? 'is-bye' : '' }}">
                                {{-- Equipo 1 --}}
                                <div class="flex justify-between items-center px-3 py-2 border-b border-gray-100 dark:border-gray-700/50 {{ $match->winner_id == $match->team1_id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                    <span class="text-sm font-bold truncate {{ $match->winner_id == $match->team1_id ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $match->team1->team_name ?? 'Vacante' }}
                                    </span>
                                    <span class="font-mono text-sm font-bold {{ $match->winner_id == $match->team1_id ? 'text-indigo-600' : 'text-gray-400' }}">
                                        {{ $match->score1 ?? '-' }}
                                    </span>
                                </div>
                                
                                {{-- Equipo 2 --}}
                                <div class="flex justify-between items-center px-3 py-2 {{ $match->winner_id == $match->team2_id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                    @if($isBye)
                                        <span class="text-xs italic text-gray-400">Pasa directo &rarr;</span>
                                    @else
                                        <span class="text-sm font-bold truncate {{ $match->winner_id == $match->team2_id ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $match->team2->team_name ?? 'Vacante' }}
                                        </span>
                                        <span class="font-mono text-sm font-bold {{ $match->winner_id == $match->team2_id ? 'text-indigo-600' : 'text-gray-400' }}">
                                            {{ $match->score2 ?? '-' }}
                                        </span>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </section>

                {{-- CONECTORES (Decorativos, aria-hidden) --}}
                @if(!$loop->last)
                    <div class="connector-column" aria-hidden="true">
                        @php
                            $nextRoundCount = count($matches) / 2;
                        @endphp
                        
                        @for($i = 0; $i < $nextRoundCount; $i++)
                            <div class="connector-block">
                                <div class="bracket-line"></div>
                                <div class="connector-bridge"></div>
                            </div>
                        @endfor
                    </div>
                @endif

            @endforeach
            
            {{-- ASIDE: Bloque Campeón --}}
            @php $finalMatch = end($rounds)[0] ?? null; @endphp
            @if($finalMatch && $finalMatch->winner)
                <aside class="flex flex-col justify-center ml-8 animate-fade-in-up" aria-label="Ganador del Torneo">
                    <div class="text-center">
                        <div class="inline-block p-4 bg-yellow-100 rounded-full mb-2 shadow-lg border-2 border-yellow-400">
                            🏆
                        </div>
                        <div class="text-[10px] font-black uppercase text-yellow-600 tracking-widest">Campeón</div>
                        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-600">
                            {{ $finalMatch->winner->team_name }}
                        </h1>
                    </div>
                </aside>
            @endif

        </section>
    </main>

    <script>
        // Auto-refresh simple
        setInterval(() => {
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newBracket = doc.getElementById('bracket-container');
                    const currentBracket = document.getElementById('bracket-container');
                    if(newBracket && currentBracket.innerHTML !== newBracket.innerHTML) {
                        currentBracket.innerHTML = newBracket.innerHTML;
                    }
                });
        }, 10000); 
    </script>
</x-app-layout>