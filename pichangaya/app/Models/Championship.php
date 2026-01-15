<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'start_date', 'location', 
        'prize_description', 'status', 'admin_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    // Equipos inscritos
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'championship_teams')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Creador del torneo
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}