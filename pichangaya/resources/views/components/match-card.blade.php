@props(['match', 'isFinal' => false])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border {{ $isFinal ? 'border-yellow-400 ring-2 ring-yellow-200' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden relative group">
    
    {{-- Formulario para Admin (Solo si no hay ganador aún) --}}
    @if(auth()->check() && auth()->user()->role === 'admin' && !$match->winner_id && $match->team1 && $match->team2)
        <div class="absolute inset-0 bg-white/90 dark:bg-gray-800/90 z-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
            <form action="{{ route('admin.matches.update', $match) }}" method="POST" class="flex items-center gap-2 p-2">
                @csrf
                <input type="number" name="score1" class="w-12 h-8 text-center text-sm border-gray-300 rounded" placeholder="Local">
                <span class="font-bold text-gray-500">-</span>
                <input type="number" name="score2" class="w-12 h-8 text-center text-sm border-gray-300 rounded" placeholder="Visit">
                <button type="submit" class="bg-green-500 text-white p-1 rounded hover:bg-green-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </form>
        </div>
    @endif

    {{-- Equipo 1 --}}
    <div class="flex justify-between items-center px-3 py-2 border-b border-gray-100 dark:border-gray-700 {{ $match->winner_id == $match->team1_id ? 'bg-green-50 dark:bg-green-900/30' : '' }}">
        <span class="text-sm font-bold truncate {{ $match->team1 ? 'text-gray-800 dark:text-white' : 'text-gray-400 italic' }}">
            {{ $match->team1->team_name ?? 'Esperando...' }}
        </span>
        <span class="font-mono font-bold {{ $match->winner_id == $match->team1_id ? 'text-green-600' : 'text-gray-500' }}">
            {{ $match->score1 ?? '-' }}
        </span>
    </div>

    {{-- Equipo 2 --}}
    <div class="flex justify-between items-center px-3 py-2 {{ $match->winner_id == $match->team2_id ? 'bg-green-50 dark:bg-green-900/30' : '' }}">
        <span class="text-sm font-bold truncate {{ $match->team2 ? 'text-gray-800 dark:text-white' : 'text-gray-400 italic' }}">
            {{ $match->team2->team_name ?? 'Esperando...' }}
        </span>
        <span class="font-mono font-bold {{ $match->winner_id == $match->team2_id ? 'text-green-600' : 'text-gray-500' }}">
            {{ $match->score2 ?? '-' }}
        </span>
    </div>
</div>