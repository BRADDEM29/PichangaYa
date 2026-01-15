<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo_path', 'captain_id', 'team_code'];

    // Relación: Quién es el capitán
    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    // Relación: Miembros del equipo
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    // Relación: Campeonatos en los que participa
    public function championships()
    {
        return $this->belongsToMany(Championship::class, 'championship_teams')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
