<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    use HasFactory;

    // 🟢 FORZAMOS EL NOMBRE DE LA TABLA TAL CUAL ESTÁ EN TU MIGRACIÓN
    protected $table = 'tournament_teams';

    protected $fillable = [
        'tournament_id',
        'team_name',
        'logo_path',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}