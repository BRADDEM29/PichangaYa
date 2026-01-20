<x-app-layout>
    {{-- 
       ESTILOS CRÍTICOS PARA LAS LÍNEAS 
       Estos estilos garantizan que no importa si hay 3, 5 o 100 equipos,
       las líneas siempre estarán centradas.
    --}}
    <style>
        .bracket-wrapper {
            display: flex;
            padding: 40px;
            overflow-x: auto; /* Scroll horizontal si es muy grande */
        }
        
        /* COLUMNA DE LA RONDA */
        .round-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around; /* LA MAGIA: Distribuye verticalmente */
            width: 260px; /* Ancho tarjeta */
            flex-shrink: 0;
        }

        /* CONECTOR ENTRE RONDAS */
        .connector-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 50px; /* Ancho de las líneas */
            flex-shrink: 0;
        }

        /* BLOQUE DE LÍNEAS (Para cada par de partidos) */
        .connector-block {
            flex-grow: 1; /* Ocupa todo el espacio disponible */
            position: relative;
            display: flex;
            align-items: center; /* Centra la línea horizontal de salida */
        }
        
        /* DIBUJO DE LA LÍNEA (Forma de Llave ]) */
        .bracket-line {
            position: absolute;
            right: 0;
            top: 25%; /* Empieza al 25% de la altura del bloque (centro del partido 1) */
            bottom: 25%; /* Termina al 25% desde abajo (centro del partido 2) */
            width: 100%;
            border-right: 2px solid #6366f1; /* Color Indigo */
            border-top: 2px solid #6366f1;
            border-bottom: 2px solid #6366f1;
            border-top-right-radius: 12px; /* Curva moderna */
            border-bottom-right-radius: 12px;
        }

        /* LÍNEA RECTA DE SALIDA (Hacia la siguiente ronda) */
        .connector-bridge {
            position: absolute;
            right: -25px; /* Sale hacia la derecha */
            width: 25px;
            height: 2px;
            background-color: #6366f1;
        }

        /* Tarjeta del Partido */
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

        /* Estilo especial para BYES (Pase directo) */
        .match-card.is-bye {
            opacity: 0.5;
            border-style: dashed;
            background: transparent;
            box-shadow: none;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center py-2">
            <div>
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
            </div>
            
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
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen overflow-hidden">
        
        {{-- ÁREA DEL BRACKET --}}
        <div class="bracket-wrapper" id="bracket-container">
            
            {{-- 
                LÓGICA DE RENDERIZADO POR RONDAS 
                $rounds debe ser un array de arrays conteniendo los partidos.
                Ej: $rounds[1] = [Match1, Match2...], $rounds[2] = [Semi1, Semi2]
            --}}

            @foreach($rounds as $roundIndex => $matches)
                
                {{-- 1. Columna de Partidos --}}
                <div class="round-column">
                    {{-- Etiqueta de la Ronda --}}
                    <div class="text-center mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            @if($loop->last) 👑 GRAN FINAL
                            @elseif($loop->iteration == $loop->count - 1) SEMIFINAL
                            @else RONDA {{ $loop->iteration }}
                            @endif
                        </span>
                    </div>

                    @foreach($matches as $match)
                        <div class="py-3 px-1 w-full">
                            {{-- INICIO TARJETA PARTIDO --}}
                            @php
                                // Detectar si es un BYE (Equipo pasa solo)
                                $isBye = ($match->team1_id && !$match->team2_id) || (!$match->team1_id && $match->team2_id);
                            @endphp

                            <div class="match-card {{ $isBye ? 'is-bye' : '' }}">
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
                            </div>
                            {{-- FIN TARJETA PARTIDO --}}
                        </div>
                    @endforeach
                </div>

                {{-- 2. Columna de Conectores (Líneas) --}}
                {{-- Solo dibujamos conectores si NO es la última ronda (Final) --}}
                @if(!$loop->last)
                    <div class="connector-column">
                        {{-- 
                           Calculamos cuántos "bloques de conexión" necesitamos.
                           Si hay 4 partidos en esta ronda, hay 2 bloques de conexión hacia la siguiente.
                           Si hay 3 partidos (caso raro impar), el CSS Flex lo maneja, pero idealmente el backend normaliza a pares.
                        --}}
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
            
            {{-- BLOQUE CAMPEÓN (Opcional, a la derecha de la final) --}}
            @php $finalMatch = end($rounds)[0] ?? null; @endphp
            @if($finalMatch && $finalMatch->winner)
                <div class="flex flex-col justify-center ml-8 animate-fade-in-up">
                    <div class="text-center">
                        <div class="inline-block p-4 bg-yellow-100 rounded-full mb-2 shadow-lg border-2 border-yellow-400">
                            🏆
                        </div>
                        <div class="text-[10px] font-black uppercase text-yellow-600 tracking-widest">Campeón</div>
                        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-600">
                            {{ $finalMatch->winner->team_name }}
                        </h1>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        // Auto-refresh simple para ver resultados en tiempo real
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
        }, 10000); // 10 segundos
    </script>
</x-app-layout>