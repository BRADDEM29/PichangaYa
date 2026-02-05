<x-app-layout>
    <section class="py-6 sm:py-12">
        <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-2xl p-4 sm:p-8 border border-gray-100 dark:border-gray-700">
                
                {{-- CABECERA DE LA SALA --}}
                <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                            SALA DE ESPERA <span class="text-blue-500">#{{ $lobby->id }}</span>
                        </h1>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mt-1">
                            Deporte: {{ $lobby->sport->name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900/50 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-black text-green-600 dark:text-green-400 uppercase tracking-widest">
                            {{ $lobby->status }}
                        </span>
                    </div>
                </header>

                {{-- GRID DE EQUIPOS (RESPONSIVE: 1 COL EN MÓVIL, 2 EN PC) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
                    
                    {{-- COLUMNA: EQUIPO A --}}
                    <section class="bg-gray-50 dark:bg-gray-900/30 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 sm:p-6">
                        <header class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                Equipo A
                            </h3>
                            <span class="text-[10px] font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 px-2 py-1 rounded-md uppercase">
                                {{ $lobby->slots->where('team_side', 'A')->count() }} Jugadores
                            </span>
                        </header>

                        <ul class="space-y-3">
                            @foreach($lobby->slots->where('team_side', 'A') as $slot)
                                <li class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition hover:scale-[1.02]">
                                    <div class="relative">
                                        <img src="{{ $slot->user->profile_photo_url }}" alt="{{ $slot->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700">
                                        @if($slot->is_ready)
                                            <span class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                                <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase truncate">
                                        {{ $slot->user->name }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    {{-- COLUMNA: EQUIPO B --}}
                    <section class="bg-gray-50 dark:bg-gray-900/30 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 sm:p-6">
                        <header class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-black text-red-600 dark:text-red-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-6 bg-red-500 rounded-full"></span>
                                Equipo B
                            </h3>
                            <span class="text-[10px] font-bold bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-300 px-2 py-1 rounded-md uppercase">
                                {{ $lobby->slots->where('team_side', 'B')->count() }} Jugadores
                            </span>
                        </header>

                        <ul class="space-y-3">
                            @foreach($lobby->slots->where('team_side', 'B') as $slot)
                                <li class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition hover:scale-[1.02]">
                                    <div class="relative">
                                        <img src="{{ $slot->user->profile_photo_url }}" alt="{{ $slot->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700">
                                        @if($slot->is_ready)
                                            <span class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                                <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase truncate">
                                        {{ $slot->user->name }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                </div>

                {{-- PIE DE SALA (OPCIONAL) --}}
                <footer class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
                        PichangaYa - Sistema de Matchmaking
                    </p>
                </footer>

            </div>
        </article>
    </section>
</x-app-layout>