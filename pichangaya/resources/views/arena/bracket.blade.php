<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\arena\bracket.blade.php --}}
    
    {{-- CSS de la Librería --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css" />

    @php
        $now = now();
        $start = \Carbon\Carbon::parse($tournament->start_date);
        
        $reserva = \App\Models\Reserva::where('cancha_id', $tournament->cancha_id)
            ->where('start_time', $tournament->start_date)
            ->first();
        
        $end = $reserva ? \Carbon\Carbon::parse($reserva->end_time) : $start->copy()->addHours(3);
        
        // Colores para Modo Claro (Default) y Oscuro (Dark)
        $statusLabel = 'Programado';
        // Light: Azul suave | Dark: Azul transparente
        $statusClasses = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20';
        $statusIcon = 'M12 6v6h45m-45 0a9 9 0 11-18 0 9 9 0 0118 0z'; // Clock
        $isAbandoned = false;

        if ($tournament->status === 'finished') {
            $statusLabel = 'Finalizado';
            // Light: Verde suave | Dark: Esmeralda transparente
            $statusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20';
            $statusIcon = 'M5 12l5 5l10 -10'; // Check (Tabler style)
        } elseif ($now->between($start, $end) && $tournament->status === 'active') {
            $statusLabel = 'En Curso';
            // Light: Verde intenso | Dark: Verde neón
            $statusClasses = 'bg-green-50 text-green-700 border-green-200 animate-pulse dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20';
            $statusIcon = 'M7 4v16l13 -8z'; // Play (Tabler style)
        } elseif ($now->gt($end) && $tournament->status !== 'finished') {
            $statusLabel = 'Sin Ganador';
            // Light: Rojo suave | Dark: Rojo transparente
            $statusClasses = 'bg-red-50 text-red-700 border-red-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20';
            $statusIcon = 'M12 9v2m0 4v.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3l-6.928-12c-.77-1.333-2.694-1.333-3.464 0l-6.928 12c-.77 1.333.192 3 1.732 3z'; // Alert Triangle
            $isAbandoned = true;
        }
    @endphp

    {{-- CONTENEDOR PRINCIPAL: Fondo Blanco (Light) / Fondo Azul Oscuro (Dark) --}}
    <div class="min-h-screen bg-gray-50 text-gray-900 font-sans selection:bg-indigo-100 selection:text-indigo-900 dark:bg-[#0f172a] dark:text-slate-200 dark:selection:bg-indigo-500 dark:selection:text-white pb-12 transition-colors duration-300">
        
        {{-- HEADER --}}
        <header class="relative bg-white border-b border-gray-200 dark:bg-slate-900 dark:border-slate-800 shadow-sm dark:shadow-none transition-colors duration-300">
            {{-- Degradado solo visible en Dark Mode --}}
            <div class="absolute inset-0 hidden dark:block bg-gradient-to-r from-indigo-900/10 via-slate-900 to-slate-900"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    
                    {{-- Info Principal --}}
                    <article>
                        <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-gray-900 dark:text-transparent dark:bg-clip-text dark:bg-gradient-to-r dark:from-white dark:to-slate-400 mb-2">
                            {{ $tournament->name }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-gray-500 dark:text-slate-400">
                            @if($tournament->cancha)
                                <span class="flex items-center gap-1.5 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 dark:bg-slate-800/50 dark:border-slate-700/50">
                                    {{-- Icono Map Pin --}}
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $tournament->cancha->name }}
                                </span>
                            @endif
                            <time class="flex items-center gap-1.5 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 dark:bg-slate-800/50 dark:border-slate-700/50">
                                {{-- Icono Calendar --}}
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $start->format('d M, Y') }}
                            </time>
                        </div>
                    </article>

                    {{-- Estado y Reloj --}}
                    <aside class="flex flex-col items-start lg:items-end gap-3 w-full lg:w-auto bg-gray-50 p-4 rounded-2xl border border-gray-200 lg:bg-transparent lg:p-0 lg:border-none dark:bg-slate-800/30 dark:border-slate-700/50">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border {{ $statusClasses }} text-[10px] font-black uppercase tracking-widest transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $statusIcon }}" />
                            </svg>
                            {{ $statusLabel }}
                        </span>

                        <div class="text-left lg:text-right">
                            <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block mb-0.5">Horario</span>
                            <div class="flex items-center gap-2 text-lg sm:text-xl font-mono font-bold text-gray-900 dark:text-white">
                                <time datetime="{{ $start->toIso8601String() }}">{{ $start->format('h:i A') }}</time>
                                <span class="text-gray-400 dark:text-slate-600">-</span>
                                <time datetime="{{ $end->toIso8601String() }}" class="text-gray-500 dark:text-slate-400">{{ $end->format('h:i A') }}</time>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            @if($isAbandoned)
                {{-- ALERTA DE ABANDONO (Light: Rojo suave | Dark: Rojo neón) --}}
                <section class="relative overflow-hidden rounded-2xl border border-red-100 bg-red-50 p-8 text-center sm:p-12 dark:border-rose-500/20 dark:bg-rose-500/5 transition-colors">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 ring-1 ring-red-200 mb-6 dark:bg-rose-500/10 dark:ring-rose-500/20">
                        <svg class="h-8 w-8 text-red-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl mb-2">Torneo Finalizado sin Ganador Oficial</h2>
                    <p class="mx-auto max-w-lg text-sm text-gray-600 dark:text-slate-400 mb-8">
                        El tiempo reglamentario ha concluido y no se registró un resultado final en el sistema. Es posible que el torneo no se haya completado.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 border border-gray-300 transition-all dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 dark:border-slate-700">
                            Volver al Inicio
                        </a>
                        
                        {{-- BOTÓN PARA MOSTRAR HISTORIAL --}}
                        <button onclick="document.getElementById('bracket-section').classList.remove('hidden'); this.style.display='none';" class="inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/20 dark:hover:bg-indigo-500">
                            {{-- Icono History (Tabler) --}}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2 2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                            </svg>
                            Ver Historial de Partidos
                        </button>
                    </div>
                </section>
            @endif

            {{-- BRACKET SECTION (Oculto por defecto si es abandonado) --}}
            <section id="bracket-section" class="{{ $isAbandoned ? 'hidden' : '' }} bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 dark:bg-slate-800/50 dark:backdrop-blur-sm dark:border-slate-700/50 dark:shadow-none transition-colors">
                
                <header class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-gray-50 dark:bg-slate-800/80 dark:border-slate-700/50">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-700 dark:text-slate-200 flex items-center gap-2">
                        {{-- Icono Trophy --}}
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 21l4-4 4 4M12 17V3M6 3h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        Tabla de Resultados
                    </h3>
                    @if($tournament->status !== 'finished')
                        <div class="flex items-center gap-2 px-2 py-1 bg-green-50 rounded-full border border-green-200 dark:bg-green-500/10 dark:border-green-500/20">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <span class="text-[10px] font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">Live</span>
                        </div>
                    @endif
                </header>

                <div class="p-4 sm:p-8 overflow-x-auto custom-scrollbar">
                    <div id="bracket-gfx" class="min-w-max mx-auto"></div>
                </div>
            </section>

        </main>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js"></script>

    <script>
        $(function() {
            var data = @json($bracketData);

            // Verificamos si hay modo oscuro activo en el HTML (clase 'dark')
            // Opcional: podrías escuchar cambios, pero el CSS se encarga de la mayoría
            
            $('#bracket-gfx').bracket({
                init: data,
                skipConsolationRound: true, 
                teamWidth: 150, 
                scoreWidth: 35,
                matchMargin: 50,
                roundMargin: 60,
                decorator: {
                    edit: function() {}, 
                    render: function(container, data, score, state) {
                        switch(state) {
                            case "empty-bye":
                                container.append('<span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">BYE</span>');
                                return;
                            case "empty-tbd":
                                container.append('<span class="text-[10px] font-bold text-gray-500 dark:text-slate-600 uppercase tracking-wider">Esperando...</span>');
                                return;
                            case "entry-no-score":
                            case "entry-default-win":
                            case "entry-complete":
                                container.append('<span class="truncate block w-full font-semibold text-sm">' + data + '</span>');
                                return;
                        }
                    }
                }
            });
        });
    </script>
    
    <style>
        /* Tipografía Base */
        .jQBracket { font-family: inherit; font-size: 14px; }
        
        /* === ESTILOS POR DEFECTO (LIGHT MODE) === */
        
        /* Equipos */
        .jQBracket .team {
            background-color: #ffffff !important; /* White */
            color: #1f2937 !important; /* Gray 800 */
            border: 1px solid #e5e7eb !important; /* Gray 200 */
            border-radius: 6px 0 0 6px;
            padding: 8px 10px;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        
        /* Puntaje */
        .jQBracket .score {
            background-color: #f9fafb !important; /* Gray 50 */
            color: #d97706 !important; /* Amber 600 */
            border: 1px solid #e5e7eb !important;
            border-left: 1px solid #e5e7eb !important;
            border-radius: 0 6px 6px 0;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            padding: 8px 0;
            font-weight: 700;
        }

        /* Ganador */
        .jQBracket .team.winner {
            background-color: #ecfdf5 !important; /* Emerald 50 */
            border-color: #10b981 !important; /* Emerald 500 */
            color: #065f46 !important; /* Emerald 800 */
        }
        .jQBracket .score.winner {
            background-color: #10b981 !important; /* Emerald 500 */
            border-color: #10b981 !important;
            color: #ffffff !important;
        }

        /* Conectores */
        .jQBracket .connector {
            border-color: #9ca3af !important; /* Gray 400 */
            border-width: 2px !important;
        }
        
        .jQBracket .team:hover {
            background-color: #f3f4f6 !important; /* Gray 100 */
        }

        /* === ESTILOS DARK MODE (Usamos la clase .dark del padre) === */
        
        :root.dark .jQBracket .team {
            background-color: #1e293b !important; /* Slate 800 */
            color: #f1f5f9 !important; /* Slate 100 */
            border-color: #334155 !important; /* Slate 700 */
        }
        
        :root.dark .jQBracket .score {
            background-color: #0f172a !important; /* Slate 900 */
            color: #fbbf24 !important; /* Amber 400 */
            border-color: #334155 !important;
            border-left-color: #334155 !important;
        }

        :root.dark .jQBracket .team.winner {
            background-color: #064e3b !important; /* Emerald 900 */
            border-color: #059669 !important; /* Emerald 600 */
            color: #fff !important;
        }
        
        :root.dark .jQBracket .score.winner {
            background-color: #065f46 !important; /* Emerald 800 */
            border-color: #059669 !important;
            color: #fff !important;
        }

        :root.dark .jQBracket .connector {
            border-color: #475569 !important; /* Slate 600 */
        }
        
        :root.dark .jQBracket .team:hover {
            background-color: #334155 !important; /* Slate 700 */
        }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        :root.dark .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        :root.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
    </style>
    <footer class="relative z-10">
        <x-footer />
    </footer>
</x-app-layout>