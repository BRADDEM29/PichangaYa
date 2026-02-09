{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\arena\index.blade.php --}}

<x-app-layout>
    {{-- LOGICA DE SEPARACIÓN POR FECHA Y ESTADO --}}
    @php
        $now = now()->startOfDay(); // Comparamos desde el inicio del día actual

        // 1. Torneos Activos: No finalizados Y (Fecha es Hoy o Futuro)
        $activeTournaments = $championships->filter(function ($t) use ($now) {
            $startDate = \Carbon\Carbon::parse($t->start_date)->startOfDay();
            return $t->status !== 'finished' && $startDate->gte($now);
        });

        // 2. Torneos Pasados: Finalizados O (Fecha es Ayer o Pasado)
        $pastTournaments = $championships->filter(function ($t) use ($now) {
            $startDate = \Carbon\Carbon::parse($t->start_date)->startOfDay();
            return $t->status === 'finished' || $startDate->lt($now);
        });
    @endphp

    <x-slot name="header">
        <hgroup class="flex items-center gap-3">
            {{-- ICONO TROFEO SOLICITADO --}}
            <span class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-500/30 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 21l8 0" />
                    <path d="M12 17l0 4" />
                    <path d="M7 4l10 0" />
                    <path d="M17 4v8a5 5 0 0 1 -10 0v-8" />
                    <path d="M3 9a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 9a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                </svg>
            </span>
            <div>
                <h1 class="font-black text-2xl text-gray-800 dark:text-gray-100 leading-tight uppercase tracking-tight">
                    {{ __('Arena de Campeonatos') }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide uppercase">
                    Gestiona y visualiza los torneos
                </p>
            </div>
        </hgroup>
    </x-slot>

    <main class="py-10 bg-gray-50 dark:bg-[#0f172a] min-h-screen">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            {{-- SECCIÓN 1: BUSCADOR --}}
            <section aria-label="Buscador de Partidas">
                @livewire('arena.match-finder')
            </section>

            {{-- CASO: NO HAY NINGÚN TORNEO (NI ACTIVO NI PASADO) --}}
            @if($championships->isEmpty())
                <article class="bg-white dark:bg-gray-800 rounded-2xl p-16 text-center border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Sin Torneos Registrados</h3>
                    <p class="text-gray-500 mt-2">No hay campeonatos activos ni pasados en este momento.</p>
                </article>
            @else

                {{-- SECCIÓN 2: TORNEOS ACTIVOS (SOLO SI HAY) --}}
                @if($activeTournaments->isNotEmpty())
                    <section aria-labelledby="active-tournaments-heading">
                        <header class="flex items-center gap-3 mb-8 border-l-4 border-indigo-600 pl-4">
                            <h2 id="active-tournaments-heading" class="text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                                Torneos Activos
                            </h2>
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold border border-indigo-200">
                                {{ $activeTournaments->count() }}
                            </span>
                        </header>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($activeTournaments as $torneo)
                                <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 border border-gray-100 dark:border-gray-700 group flex flex-col h-full transform hover:-translate-y-1">
                                    {{-- Header Imagen --}}
                                    <header class="h-40 bg-gray-900 relative flex items-center justify-center overflow-hidden">
                                        <span class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-900 opacity-90"></span>
                                        <svg class="absolute inset-0 w-full h-full opacity-20" aria-hidden="true"><pattern id="pattern-active-{{$torneo->id}}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" class="text-white" fill="currentColor" /></pattern><rect width="100%" height="100%" fill="url(#pattern-active-{{$torneo->id}})" /></svg>
                                        <h3 class="text-2xl font-black text-white uppercase tracking-widest z-10 text-center px-6 leading-tight drop-shadow-md relative">
                                            {{ $torneo->name }}
                                        </h3>
                                    </header>

                                    {{-- Cuerpo --}}
                                    <section class="p-6 flex-1 flex flex-col justify-between">
                                        <div class="space-y-4">
                                            <header class="flex justify-between items-center">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-green-500/10 text-green-600 border border-green-500/20">
                                                    <span class="relative flex h-2 w-2 mr-1">
                                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                    </span>
                                                    En Curso
                                                </span>
                                                <time class="text-gray-500 dark:text-gray-400 text-xs font-bold flex items-center gap-1 uppercase">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ \Carbon\Carbon::parse($torneo->start_date)->format('d M, Y') }}
                                                </time>
                                            </header>

                                            @if($torneo->prize_description)
                                                <aside class="bg-yellow-50 dark:bg-yellow-900/10 p-3 rounded-xl flex items-start gap-3 border border-yellow-100 dark:border-yellow-700/30">
                                                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.699-3.181a1 1 0 011.827.954L17.187 7H18a1 1 0 110 2h-3.6l3.938 7.444a1 1 0 01-1.8.9L12 10.777l-4.538 6.567a1 1 0 01-1.8-.9L9.6 9H6a1 1 0 010-2h.813l-1.29-3.222a1 1 0 011.827-.954l1.699 3.181L13 4.323V3a1 1 0 011-1h-4z" clip-rule="evenodd" /></svg>
                                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase leading-snug">
                                                        {{ $torneo->prize_description }}
                                                    </p>
                                                </aside>
                                            @endif
                                        </div>

                                        <footer class="mt-6">
                                            <a href="{{ route('arena.show', $torneo->id) }}" class="w-full group bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/30">
                                                Ver Bracket
                                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                            </a>
                                        </footer>
                                    </section>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- SECCIÓN 3: HISTORIAL DE TORNEOS (SOLO SI HAY) --}}
                @if($pastTournaments->isNotEmpty())
                    <section aria-labelledby="past-tournaments-heading" class="pt-12 border-t border-gray-200 dark:border-gray-800">
                        <header class="flex items-center gap-3 mb-8 pl-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h2 id="past-tournaments-heading" class="text-xl font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tight">
                                Historial de Torneos
                            </h2>
                        </header>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($pastTournaments as $torneo)
                                <article class="bg-gray-50 dark:bg-gray-800/50 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors group flex flex-col h-full opacity-80 hover:opacity-100">
                                    {{-- Header Simple Grayscale --}}
                                    <header class="h-24 bg-gray-200 dark:bg-gray-700 relative flex items-center justify-center overflow-hidden grayscale group-hover:grayscale-0 transition-all duration-500">
                                        <span class="absolute inset-0 bg-gradient-to-r from-gray-700 to-gray-800 opacity-90"></span>
                                        <h3 class="text-lg font-bold text-white uppercase tracking-wider z-10 text-center px-4 relative">
                                            {{ $torneo->name }}
                                        </h3>
                                    </header>

                                    <section class="p-5 flex-1 flex flex-col">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                Finalizado
                                            </span>
                                            <time class="text-gray-400 text-[10px] font-bold uppercase">
                                                {{ \Carbon\Carbon::parse($torneo->start_date)->format('d/m/Y') }}
                                            </time>
                                        </div>

                                        <footer class="mt-auto">
                                            <a href="{{ route('arena.show', $torneo->id) }}" class="w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                                Ver Resultados
                                            </a>
                                        </footer>
                                    </section>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

        </section>
    </main>

    <footer class="relative z-10">
        <x-footer />
    </footer>
</x-app-layout>