<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Models\TournamentTeam.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_name',
        'logo_path',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}