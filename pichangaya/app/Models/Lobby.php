<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    use HasFactory;

    // Aseguramos que los campos necesarios sean permitidos
    protected $fillable = [
        'sport_id', 
        'district_id', 
        'status', 
        'expires_at', 
        'max_slots',
        'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Relación: Deporte
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    // Relación: Ubicación
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Relación: Jugadores (Slots)
    public function slots()
    {
        return $this->hasMany(LobbySlot::class);
    }
    
    // Helper: Total de jugadores en la sala
    public function getPlayerCountAttribute()
    {
        return $this->slots()->count();
    }

    // NUEVO: Helper para contar cuántos han dado "Check" (Confirmado)
    public function getConfirmedCountAttribute()
    {
        return $this->slots()->whereNotNull('confirmed_at')->count();
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}