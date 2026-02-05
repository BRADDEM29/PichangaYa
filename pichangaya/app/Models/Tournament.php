<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'status',
        'created_by',
        'prize_description',
        'cancha_id',
    ];

    // Relación con los partidos
    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    
    public function teams()
    {
        // Importante: Usar TournamentTeam::class, NO Team::class
        return $this->hasMany(TournamentTeam::class, 'tournament_id');
    }
}