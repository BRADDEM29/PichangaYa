<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    use HasFactory;

    protected $fillable = ['sport_id', 'district_id', 'status', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Deporte (Fútbol 7, Vóley)
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    // Ubicación
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Jugadores dentro
    public function slots()
    {
        return $this->hasMany(LobbySlot::class);
    }
    
    // Helper: Contar jugadores
    public function getPlayerCountAttribute()
    {
        return $this->slots()->count();
    }
}