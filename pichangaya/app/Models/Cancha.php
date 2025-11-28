<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    // 1. Asignación Masiva
    // Definimos qué campos tienen permiso para ser guardados directamente.
    // ¡Es vital incluir 'user_id' aquí para que funcione tu controlador!
    protected $fillable = [
        'name',
        'address',
        'price_per_hour',
        'description',
        'sport_id',
        'district_id',
        'user_id', 
    ];

    // 2. Relaciones (Eloquent Relationships)
    
    // Una Cancha pertenece a un Usuario (Dueño)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una Cancha pertenece a un Deporte
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    // Una Cancha pertenece a un Distrito
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}