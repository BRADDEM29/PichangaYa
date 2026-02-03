<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    use HasFactory;

    // 🔴 AQUÍ FALTABA 'max_slots' y 'created_by'
    // Al agregarlos, Laravel ya permite guardar el número 2 (o 10, o 12)
    protected $fillable = [
        'sport_id', 
        'district_id', 
        'status', 
        'expires_at', 
        'max_slots',   // <--- ¡ESTO FALTABA!
        'created_by'   // <--- Esto también es importante para saber quién creó la sala
    ];

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
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}