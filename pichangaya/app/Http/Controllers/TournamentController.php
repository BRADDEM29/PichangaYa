<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\MatchGame; // Asumiremos que el modelo se llama MatchGame para no chocar con palabra reservada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    // Vista para crear torneo (Solo Admin/Dueño)
    public function create() {
        return view('admin.tournaments.create');
    }

    // Guardar torneo y generar bracket vacío
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'teams' => 'required|array|min:8|max:8', // Forzamos 8 equipos para Cuartos de Final
            'teams.*' => 'required|string'
        ]);

        DB::transaction(function () use ($request) {
            $tournament = Tournament::create([
                'name' => $request->name,
                'created_by' => auth()->id(),
                'status' => 'active'
            ]);

            // Crear Equipos
            $teams = [];
            foreach($request->teams as $teamName) {
                $teams[] = TournamentTeam::create([
                    'tournament_id' => $tournament->id,
                    'team_name' => $teamName
                ]);
            }

            // --- GENERAR BRACKET AUTOMÁTICO (Cuartos -> Semis -> Final) ---
            
            // 1. Crear Final (Partido 7)
            $final = \App\Models\Matches::create([
                'tournament_id' => $tournament->id,
                'phase' => 'final',
                'match_number' => 1
            ]);

            // 2. Crear Semifinales (Partidos 5 y 6) -> Llevan al Final
            $semi1 = \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'semi_final', 'match_number' => 1, 'next_match_id' => $final->id]);
            $semi2 = \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'semi_final', 'match_number' => 2, 'next_match_id' => $final->id]);

            // 3. Crear Cuartos (Partidos 1,2,3,4) -> Llevan a Semis
            // Asignamos los equipos aleatoriamente o en orden
            \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'quarter_final', 'match_number' => 1, 'next_match_id' => $semi1->id, 'team1_id' => $teams[0]->id, 'team2_id' => $teams[1]->id]);
            \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'quarter_final', 'match_number' => 2, 'next_match_id' => $semi1->id, 'team1_id' => $teams[2]->id, 'team2_id' => $teams[3]->id]);
            \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'quarter_final', 'match_number' => 3, 'next_match_id' => $semi2->id, 'team1_id' => $teams[4]->id, 'team2_id' => $teams[5]->id]);
            \App\Models\Matches::create(['tournament_id' => $tournament->id, 'phase' => 'quarter_final', 'match_number' => 4, 'next_match_id' => $semi2->id, 'team1_id' => $teams[6]->id, 'team2_id' => $teams[7]->id]);
        });

        return redirect()->route('admin.dashboard')->with('success', 'Torneo Creado y Bracket Generado');
    }

    // Vista Pública del Bracket
    public function show(Tournament $tournament) {
        $matches = $tournament->matches()->with(['team1', 'team2', 'winner'])->get();
        return view('tournaments.bracket', compact('tournament', 'matches'));
    }
}