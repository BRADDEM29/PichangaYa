<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Models\Tournament.php
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
        'status', // 'open', 'active', 'finished'
        'created_by',
        'prize_description',
    ];

    // Relación con los partidos
    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    // Relación con los equipos
    public function teams()
    {
        return $this->hasMany(TournamentTeam::class);
    }
}