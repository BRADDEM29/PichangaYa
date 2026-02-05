<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\TournamentController.php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\Matches;
use App\Models\Reserva;
use App\Models\Cancha; // Importado explícitamente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::latest()->get();
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        // Uso App\Models\Cancha directamente o el alias importado arriba
        $canchas = Cancha::where('is_active', true)->with('district')->get();
        return view('admin.tournaments.create', compact('canchas'));
    }

    /**
     * LÓGICA PARA LIBRERÍA (Potencias de 2: 4, 8, 16...)
     * Integra bloqueo automático de cancha mediante una Reserva.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'teams' => 'required|array|min:2',
            'cancha_id' => 'required|exists:canchas,id',
            'duration' => 'required|integer|min:1', 
        ]);

        $teamNames = array_values(array_filter($request->teams, fn($t) => !empty($t)));
        $totalTeams = count($teamNames);

        return DB::transaction(function () use ($request, $teamNames, $totalTeams) {
            // 1. Crear el Torneo
            $tournament = Tournament::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'cancha_id' => $request->cancha_id,
                'created_by' => auth()->id(),
                'status' => 'active',
                'slug' => Str::slug($request->name) . '-' . uniqid()
            ]);

            // 2. BLOQUEAR LA CANCHA (Crear Reserva Automática)
            Reserva::create([
                'user_id'      => auth()->id(),
                'cancha_id'    => $request->cancha_id,
                'start_time'   => $request->start_date,
                // 🟢 SOLUCIÓN APLICADA AQUÍ: (int) forzar entero
                'end_time'     => Carbon::parse($request->start_date)->addHours((int) $request->duration),
                'status'       => 'fully_paid',
                'payment_type' => 'tournament', // Para pintar morado
                'total_price'  => 0,
                // 'description' => ... (Eliminado para evitar errores de BD)
            ]);

            // 3. Calcular tamaño del Bracket (4, 8, 16, 32)
            $bracketSize = pow(2, ceil(log($totalTeams, 2))); 
            
            // 4. Preparar los emparejamientos de Ronda 1
            $pairings = [];
            $teamsCopy = $teamNames;
            $matchCountR1 = $bracketSize / 2;
            $byesNeeded = $bracketSize - $totalTeams;

            for ($i = 0; $i < $matchCountR1; $i++) {
                $teamA_Name = array_shift($teamsCopy);
                $teamB_Name = null;
                
                if ($byesNeeded > 0) {
                    $byesNeeded--;
                } else {
                    $teamB_Name = array_shift($teamsCopy);
                }

                $t1 = $teamA_Name ? TournamentTeam::create(['tournament_id' => $tournament->id, 'team_name' => $teamA_Name]) : null;
                $t2 = $teamB_Name ? TournamentTeam::create(['tournament_id' => $tournament->id, 'team_name' => $teamB_Name]) : null;

                $pairings[] = ['t1' => $t1, 't2' => $t2];
            }

            // 5. Generar Estructura de Partidos en BD (Recursivo hacia atrás)
            $totalRounds = log($bracketSize, 2);
            $nextRoundMatches = [];

            for ($round = $totalRounds; $round >= 1; $round--) {
                $matchesInRound = pow(2, $totalRounds - $round);
                $currentRoundMatches = [];

                for ($m = 0; $m < $matchesInRound; $m++) {
                    $nextMatchId = null;
                    if ($round < $totalRounds) {
                        $parentIndex = floor($m / 2);
                        $nextMatchId = $nextRoundMatches[$parentIndex]->id ?? null;
                    }

                    $match = Matches::create([
                        'tournament_id' => $tournament->id,
                        'round' => $round,
                        'match_number' => $m + 1,
                        'next_match_id' => $nextMatchId,
                        'phase' => $round == $totalRounds ? 'final' : 'elimination'
                    ]);
                    $currentRoundMatches[] = $match;

                    if ($round == 1) {
                        $pair = $pairings[$m] ?? ['t1' => null, 't2' => null];
                        $match->team1_id = $pair['t1']?->id;
                        $match->team2_id = $pair['t2']?->id;
                        
                        if ($match->team1_id && !$match->team2_id) {
                            $match->winner_id = $match->team1_id;
                            $match->score1 = 1; 
                            $match->score2 = 0;
                            $this->advanceWinner($match, $match->team1_id);
                        } elseif (!$match->team1_id && $match->team2_id) {
                            $match->winner_id = $match->team2_id;
                            $match->score1 = 0;
                            $match->score2 = 1;
                            $this->advanceWinner($match, $match->team2_id);
                        }
                        
                        $match->save();
                    }
                }
                $nextRoundMatches = $currentRoundMatches;
            }

            return redirect()->route('admin.tournaments.index')->with('success', 'Torneo y reserva de cancha creados exitosamente.');
        });
    }

    /**
     * MUESTRA LA VISTA DE EDICIÓN (Nuevo)
     */
    public function edit(Tournament $tournament)
    {
        // Asumiendo que la relación en el modelo Tournament es 'teams' (hasMany TournamentTeam)
        $tournament->load('teams'); 
        $canchas = Cancha::where('is_active', true)->get();

        return view('admin.tournaments.edit', compact('tournament', 'canchas'));
    }

    /**
     * ACTUALIZA EL TORNEO (Nuevo)
     * Nota: Modificar equipos reiniciando el bracket es complejo. 
     * Aquí se asume actualización básica y re-sincronización simple.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'cancha_id' => 'nullable|exists:canchas,id',
        ]);

        // 1. CAPTURAR DATOS ANTIGUOS (Clave para encontrar la reserva)
        // Usamos getOriginal para asegurarnos de tener el dato puro de la BD
        $oldCanchaId = $tournament->getOriginal('cancha_id');
        $oldStartDate = $tournament->getOriginal('start_date'); 

        // 2. ACTUALIZAR EL TORNEO
        $tournament->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'cancha_id' => $request->cancha_id,
        ]);

        // ---------------------------------------------------------
        // GESTIÓN DE RESERVA (CORREGIDA PARA FECHAS Y DURACIÓN)
        // ---------------------------------------------------------
        
        if ($oldCanchaId) {
            // Formateamos la fecha antigua para que coincida con MySQL (Y-m-d H:i:s)
            $formattedOldDate = \Carbon\Carbon::parse($oldStartDate)->format('Y-m-d H:i:s');

            // Buscamos la reserva
            $reserva = \App\Models\Reserva::where('cancha_id', $oldCanchaId)
                ->where('start_time', $formattedOldDate) // Búsqueda exacta
                ->first();

            // Si no la encuentra por fecha exacta, intentamos buscarla en el mismo minuto
            if (!$reserva) {
                $reserva = \App\Models\Reserva::where('cancha_id', $oldCanchaId)
                    ->where('start_time', 'like', substr($formattedOldDate, 0, 16) . '%') 
                    ->first();
            }

            if ($reserva) {
                if ($request->cancha_id) {
                    // 🟢 CAMBIO CLAVE:
                    // Priorizamos la duración que viene del formulario ($request->duration).
                    // Si no viene (null), entonces calculamos la antigua.
                    $newDuration = $request->duration ?? $reserva->end_time->diffInHours($reserva->start_time);
                    
                    // Aseguramos que sea entero para sumar horas correctamente
                    $newDuration = (int) $newDuration;

                    $reserva->update([
                        'cancha_id' => $request->cancha_id,
                        'start_time' => $request->start_date,
                        // Aquí sumamos la NUEVA duración al inicio
                        'end_time' => \Carbon\Carbon::parse($request->start_date)->addHours($newDuration),
                    ]);
                } else {
                    // Si quitaron la cancha, borramos reserva
                    $reserva->delete();
                }
            }
        }
        // Si no tenía cancha antes pero ahora sí
        elseif (!$oldCanchaId && $request->cancha_id) {
            // Si viene una duración del formulario, la usamos. Si no, 3 horas por defecto.
            $newDuration = $request->duration ? (int) $request->duration : 3;

            \App\Models\Reserva::create([
                'cancha_id' => $request->cancha_id,
                'user_id' => auth()->id(),
                'start_time' => $request->start_date,
                'end_time' => \Carbon\Carbon::parse($request->start_date)->addHours($newDuration),
                'total_price' => 0,
                'status' => 'confirmed',
            ]);
        }

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Torneo actualizado correctamente.');
    }

    /**
     * ELIMINAR TORNEO (Nuevo)
     */
    public function destroy(Tournament $tournament)
    {
        DB::transaction(function () use ($tournament) {
            
            // 1. ELIMINAR RESERVA (BLOQUEO)
            // Como no tienes columna 'description' ni 'observaciones',
            // buscamos la reserva que coincida con la Cancha y la Hora de Inicio del torneo.
            if ($tournament->cancha_id && $tournament->start_date) {
                Reserva::where('cancha_id', $tournament->cancha_id)
                    ->where('start_time', $tournament->start_date) 
                    ->delete();
            }

            // 2. Eliminar partidos y equipos
            // (Usamos delete() directo en la relación para asegurarnos)
            $tournament->matches()->delete(); 
            $tournament->teams()->delete();
            
            // 3. Eliminar el torneo
            $tournament->delete();
        });

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Torneo eliminado y cancha liberada.');
    }

    public function show(Tournament $tournament)
    {
        $matches = Matches::where('tournament_id', $tournament->id)
            ->with(['team1', 'team2'])
            ->orderBy('round', 'asc')
            ->orderBy('match_number', 'asc')
            ->get();

        $teams = [];
        $round1 = $matches->where('round', 1);
        
        foreach($round1 as $m) {
            $t1 = $m->team1 ? $m->team1->team_name : null;
            $t2 = $m->team2 ? $m->team2->team_name : null;
            $teams[] = [$t1, $t2];
        }

        $results = [];
        $totalRounds = $matches->max('round');

        for ($r = 1; $r <= $totalRounds; $r++) {
            $roundScores = [];
            $roundMatches = $matches->where('round', $r);
            
            foreach($roundMatches as $m) {
                if (!is_null($m->score1) || !is_null($m->score2)) {
                    $roundScores[] = [intval($m->score1), intval($m->score2)];
                } else {
                    $roundScores[] = null;
                }
            }
            $results[] = $roundScores;
        }

        $bracketData = [
            'teams' => $teams,
            'results' => $results
        ];

        return view('arena.bracket', compact('tournament', 'bracketData'));
    }

    public function manage(Tournament $tournament)
    {
        $rounds = Matches::where('tournament_id', $tournament->id)
            ->with(['team1', 'team2', 'winner'])
            ->orderBy('round', 'asc')
            ->orderBy('match_number', 'asc')
            ->get()
            ->groupBy('round');

        return view('admin.tournaments.manage', compact('tournament', 'rounds'));
    }

    public function updateMatch(Request $request, Matches $match)
    {
        $request->validate([
            'score1' => 'required|integer|min:0',
            'score2' => 'required|integer|min:0',
        ]);

        $winnerId = null;
        if ($request->score1 > $request->score2) {
            $winnerId = $match->team1_id;
        } elseif ($request->score2 > $request->score1) {
            $winnerId = $match->team2_id;
        } else {
            return back()->with('error', 'Empate no permitido.');
        }

        $match->update([
            'score1' => $request->score1,
            'score2' => $request->score2,
            'winner_id' => $winnerId
        ]);

        $this->advanceWinner($match, $winnerId);
        
        if (!$match->next_match_id) {
            $match->tournament->update(['status' => 'finished']);
        }

        return back()->with('success', 'Resultado guardado.');
    }

    protected function advanceWinner($match, $winnerId) 
    {
        if ($match->next_match_id) {
            $nextMatch = Matches::find($match->next_match_id);
            if ($match->match_number % 2 != 0) {
                $nextMatch->update(['team1_id' => $winnerId]);
            } else {
                $nextMatch->update(['team2_id' => $winnerId]);
            }
        }
    }
}