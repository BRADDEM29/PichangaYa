<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            ⚙️ Administrar Torneo: {{ $tournament->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- Navegación --}}
                <div class="mb-6 flex justify-between">
                    <a href="{{ route('admin.tournaments.index') }}" class="text-indigo-600 hover:underline">&larr; Volver</a>
                    <a href="{{ route('arena.show', $tournament->id) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ver Bracket Público</a>
                </div>

                {{-- Lista de Partidos por Ronda --}}
                @foreach($rounds as $roundNumber => $matches)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold border-b-2 border-indigo-500 mb-4 pb-2">
                            Ronda {{ $roundNumber }} 
                            @if($loop->last) (FINAL) @endif
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($matches as $match)
                                {{-- Si es un BYE, no mostramos formulario --}}
                                @php
                                    $isBye = ($match->team1_id && !$match->team2_id) || (!$match->team1_id && $match->team2_id);
                                @endphp

                                <div class="border rounded-lg p-4 {{ $match->winner_id ? 'bg-gray-50' : 'bg-white shadow-md border-indigo-100' }}">
                                    <div class="text-xs text-gray-400 font-bold mb-2 text-center">Match #{{ $match->match_number }}</div>

                                    @if($isBye)
                                        <div class="text-center py-4 text-gray-400 italic">
                                            Pase Directo (Bye)
                                        </div>
                                    @elseif(!$match->team1_id || !$match->team2_id)
                                        <div class="text-center py-4 text-gray-400">
                                            Esperando rivales...
                                        </div>
                                    @else
                                        {{-- FORMULARIO PARA ACTUALIZAR --}}
                                        <form action="{{ route('admin.matches.update', $match->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-bold truncate w-24 {{ $match->winner_id == $match->team1_id ? 'text-green-600' : '' }}">
                                                    {{ $match->team1->team_name }}
                                                </span>
                                                <input type="number" name="score1" value="{{ $match->score1 }}" class="w-16 h-8 text-center border-gray-300 rounded text-sm" {{ $match->winner_id ? 'disabled' : '' }}>
                                            </div>

                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm font-bold truncate w-24 {{ $match->winner_id == $match->team2_id ? 'text-green-600' : '' }}">
                                                    {{ $match->team2->team_name }}
                                                </span>
                                                <input type="number" name="score2" value="{{ $match->score2 }}" class="w-16 h-8 text-center border-gray-300 rounded text-sm" {{ $match->winner_id ? 'disabled' : '' }}>
                                            </div>

                                            @if(!$match->winner_id)
                                                <button type="submit" class="w-full bg-indigo-600 text-white py-1 rounded text-xs uppercase font-bold hover:bg-indigo-700">
                                                    Guardar Resultado
                                                </button>
                                            @else
                                                <div class="text-center text-xs font-bold text-green-600 bg-green-100 py-1 rounded">
                                                    FINALIZADO
                                                </div>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>