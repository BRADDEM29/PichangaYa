<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden relative">
    
    {{-- EQUIPO 1 --}}
    <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 dark:border-gray-700 {{ $match->winner_id == $match->team1_id ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
        <span class="text-sm font-bold truncate {{ $match->team1 ? 'text-gray-800 dark:text-white' : 'text-gray-400 italic' }}">
            {{ $match->team1->team_name ?? 'Esperando...' }}
        </span>
        <span class="font-mono font-bold text-lg">{{ $match->score1 ?? '-' }}</span>
    </div>

    {{-- EQUIPO 2 --}}
    <div class="flex justify-between items-center px-4 py-3 {{ $match->winner_id == $match->team2_id ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
        <span class="text-sm font-bold truncate {{ $match->team2 ? 'text-gray-800 dark:text-white' : 'text-gray-400 italic' }}">
            {{ $match->team2->team_name ?? 'Esperando...' }}
        </span>
        <span class="font-mono font-bold text-lg">{{ $match->score2 ?? '-' }}</span>
    </div>

    {{-- 🔴 FORMULARIO ADMIN (SOLO APARECE SI NO HAY GANADOR Y YA HAY EQUIPOS) --}}
    @if(!$match->winner_id && $match->team1 && $match->team2)
        <div class="bg-gray-50 dark:bg-gray-900 p-2 border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.matches.update', $match->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="number" name="score1" class="w-12 h-8 text-center text-sm border-gray-300 rounded p-0" placeholder="0" required>
                <span class="text-gray-400 font-bold">:</span>
                <input type="number" name="score2" class="w-12 h-8 text-center text-sm border-gray-300 rounded p-0" placeholder="0" required>
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 rounded uppercase">
                    Guardar
                </button>
            </form>
        </div>
    @endif
</div>