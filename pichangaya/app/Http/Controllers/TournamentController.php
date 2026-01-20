<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::latest()->get();
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        $canchas = \App\Models\Cancha::where('is_active', true)->with('district')->get();
        return view('admin.tournaments.create', compact('canchas'));
    }

    /**
     * LÓGICA PARA LIBRERÍA (Potencias de 2: 4, 8, 16...)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'teams' => 'required|array|min:2',
            'cancha_id' => 'required|exists:canchas,id',
        ]);

        $teamNames = array_values(array_filter($request->teams, fn($t) => !empty($t)));
        $totalTeams = count($teamNames);

        DB::transaction(function () use ($request, $teamNames, $totalTeams) {
            $tournament = Tournament::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'cancha_id' => $request->cancha_id,
                'created_by' => auth()->id(),
                'status' => 'active',
                'slug' => Str::slug($request->name) . '-' . uniqid()
            ]);

            // 1. Calcular tamaño del Bracket (4, 8, 16, 32)
            // La librería NECESITA una potencia de 2 perfecta.
            $bracketSize = pow(2, ceil(log($totalTeams, 2))); 
            
            // 2. Preparar los emparejamientos de Ronda 1
            // Estrategia: Llenar de arriba a abajo. Si sobran espacios, son "BYEs" (Pase directo)
            // Para 5 equipos en bracket de 8: 3 Byes.
            // Match 1: T1 vs BYE
            // Match 2: T2 vs BYE
            // Match 3: T3 vs BYE
            // Match 4: T4 vs T5
            
            $pairings = [];
            $teamsCopy = $teamNames;
            $matchCountR1 = $bracketSize / 2; // Ej: 4 partidos para bracket de 8

            // Calculamos cuántos partidos reales (2 equipos) vs partidos con Bye (1 equipo)
            // Byes = TamañoBracket - TotalEquiposReal
            $byesNeeded = $bracketSize - $totalTeams;

            for ($i = 0; $i < $matchCountR1; $i++) {
                // Sacamos el Equipo A
                $teamA_Name = array_shift($teamsCopy);
                
                // Determinamos el Equipo B
                $teamB_Name = null;
                
                if ($byesNeeded > 0) {
                    // Es un BYE. El Equipo B es null.
                    $byesNeeded--;
                } else {
                    // Es partido real. Sacamos el siguiente equipo.
                    $teamB_Name = array_shift($teamsCopy);
                }

                // Crear modelos en BD
                $t1 = $teamA_Name ? TournamentTeam::create(['tournament_id' => $tournament->id, 'team_name' => $teamA_Name]) : null;
                $t2 = $teamB_Name ? TournamentTeam::create(['tournament_id' => $tournament->id, 'team_name' => $teamB_Name]) : null;

                $pairings[] = ['t1' => $t1, 't2' => $t2];
            }

            // 3. Generar Partidos en BD (Recursivo hacia atrás)
            $totalRounds = log($bracketSize, 2);
            $nextRoundMatches = [];

            for ($round = $totalRounds; $round >= 1; $round--) {
                $matchesInRound = pow(2, $totalRounds - $round);
                $currentRoundMatches = [];

                for ($m = 0; $m < $matchesInRound; $m++) {
                    // Buscar ID del partido siguiente (padre)
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

                    // Si es Ronda 1, asignamos los equipos y AUTO-WIN si es Bye
                    if ($round == 1) {
                        $pair = $pairings[$m] ?? ['t1' => null, 't2' => null];
                        $match->team1_id = $pair['t1']?->id;
                        $match->team2_id = $pair['t2']?->id;
                        
                        // LOGICA BYE: Si falta un oponente, el otro gana automáticamente
                        if ($match->team1_id && !$match->team2_id) {
                            $match->winner_id = $match->team1_id;
                            $match->score1 = 1; // Score simbólico
                            $match->score2 = 0;
                            $this->advanceWinner($match, $match->team1_id);
                        } elseif (!$match->team1_id && $match->team2_id) {
                            $match->winner_id = $match->team2_id;
                            $this->advanceWinner($match, $match->team2_id);
                        }
                        
                        $match->save();
                    }
                }
                $nextRoundMatches = $currentRoundMatches;
            }
        });

        return redirect()->route('admin.tournaments.index')->with('success', 'Torneo creado con estructura profesional.');
    }

    /**
     * Muestra el Bracket usando la Librería
     */
    public function show(Tournament $tournament)
    {
        // Obtener todos los partidos
        $matches = Matches::where('tournament_id', $tournament->id)
            ->with(['team1', 'team2'])
            ->orderBy('round', 'asc')
            ->orderBy('match_number', 'asc')
            ->get();

        // --- FORMATO PARA JQUERY-BRACKET ---
        
        // 1. TEAMS: Array de parejas de la PRIMERA RONDA
        // Ej: [["Team 1", null], ["Team 2", null], ["Team 3", null], ["Team 4", "Team 5"]]
        $teams = [];
        $round1 = $matches->where('round', 1);
        
        foreach($round1 as $m) {
            $t1 = $m->team1 ? $m->team1->team_name : null;
            $t2 = $m->team2 ? $m->team2->team_name : null;
            $teams[] = [$t1, $t2];
        }

        // 2. RESULTS: Array de arrays de scores por ronda
        $results = [];
        $totalRounds = $matches->max('round');

        // La librería pide: [[Ronda1Scores], [Ronda2Scores], [FinalScores]]
        // Score format: [1, 0] o null
        for ($r = 1; $r <= $totalRounds; $r++) {
            $roundScores = [];
            $roundMatches = $matches->where('round', $r);
            
            foreach($roundMatches as $m) {
                if (!is_null($m->score1) || !is_null($m->score2)) {
                    $roundScores[] = [intval($m->score1), intval($m->score2)];
                } else {
                    $roundScores[] = null; // No jugado
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

    // --- FUNCIONES DE ADMINISTRACIÓN ---

    public function manage(Tournament $tournament)
    {
        // Reutilizamos la lógica de agrupación por rondas para el panel admin
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

    protected function advanceWinner($match, $winnerId) {
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