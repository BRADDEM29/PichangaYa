<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'district_id', 'sport_id', 'user_id', 'photo'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // --- AGREGAR ESTA RELACIÓN ---
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
    
    // Relación: Un negocio tiene muchos deportes (A través de sus canchas)
    // NOTA: Esto lo usaremos más adelante para el filtro avanzado.
}