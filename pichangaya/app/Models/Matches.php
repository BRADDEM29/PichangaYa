<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'round',
        'phase',       
        'match_number',
        'team1_id',
        'team2_id',
        'score1',
        'score2',
        'winner_id',
        'next_match_id', // ID del partido al que avanza el ganador
    ];

    // Relación con el equipo 1
    public function team1()
    {
        return $this->belongsTo(TournamentTeam::class, 'team1_id');
    }

    // Relación con el equipo 2
    public function team2()
    {
        return $this->belongsTo(TournamentTeam::class, 'team2_id');
    }

    // Relación con el ganador
    public function winner()
    {
        return $this->belongsTo(TournamentTeam::class, 'winner_id');
    }

    // Relación con el torneo
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}