<article class="flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md group">
    
    {{-- HEADER: Info del Match --}}
    <header class="px-4 py-2 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
            Match #{{ $match->match_number }}
        </span>
        @if($match->winner_id)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-bold uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                Finalizado
            </span>
        @endif
    </header>

    {{-- CUERPO: Lista de Equipos --}}
    <div class="flex-1 flex flex-col justify-center">
        
        {{-- EQUIPO 1 --}}
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-50 dark:border-gray-700/50 
            {{ $match->winner_id == $match->team1_id ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}">
            
            <div class="flex items-center gap-2 overflow-hidden">
                @if($match->winner_id == $match->team1_id)
                    <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 013.16 5.337a45.6 45.6 0 012.006-.343v.256zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 01-2.863 3.207 6.72 6.72 0 00.857-3.294z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="text-sm font-bold truncate {{ $match->team1 ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 italic text-xs' }}">
                    {{ $match->team1->team_name ?? 'Esperando rival...' }}
                </span>
            </div>
            
            <span class="font-mono font-bold text-lg text-gray-700 dark:text-gray-300">
                {{ $match->score1 ?? '-' }}
            </span>
        </div>

        {{-- EQUIPO 2 --}}
        <div class="flex justify-between items-center px-4 py-3 {{ $match->winner_id == $match->team2_id ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}">
            <div class="flex items-center gap-2 overflow-hidden">
                @if($match->winner_id == $match->team2_id)
                    <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 013.16 5.337a45.6 45.6 0 012.006-.343v.256zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 01-2.863 3.207 6.72 6.72 0 00.857-3.294z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="text-sm font-bold truncate {{ $match->team2 ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 italic text-xs' }}">
                    {{ $match->team2->team_name ?? 'Esperando rival...' }}
                </span>
            </div>
            
            <span class="font-mono font-bold text-lg text-gray-700 dark:text-gray-300">
                {{ $match->score2 ?? '-' }}
            </span>
        </div>
    </div>

    {{-- FOOTER: Formulario Admin (Solo si se puede editar) --}}
    @if(!$match->winner_id && $match->team1 && $match->team2)
        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.matches.update', $match->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PUT')
                
                {{-- Inputs estilo Marcador --}}
                <div class="flex items-center gap-1 w-full">
                    <input type="number" name="score1" value="{{ $match->score1 }}" min="0" placeholder="0" 
                        class="w-full h-9 text-center text-sm font-bold border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                    
                    <span class="text-gray-400 font-black text-xs px-1">VS</span>
                    
                    <input type="number" name="score2" value="{{ $match->score2 }}" min="0" placeholder="0" 
                        class="w-full h-9 text-center text-sm font-bold border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                </div>

                {{-- Botón Guardar Icono --}}
                <button type="submit" class="flex-shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow-sm transition-colors" title="Guardar Resultado">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </button>
            </form>
        </div>
    @endif
</article>