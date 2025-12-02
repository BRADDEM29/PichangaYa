<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Importaciones de Spatie
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
// Importaciones de Relaciones
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// 🔴 CORRECCIÓN 1: 'implements HasMedia' debe ir aquí arriba
class Cancha extends Model implements HasMedia
{
    use HasFactory;
    
    // 🔴 CORRECCIÓN 2: 'use InteractsWithMedia' es el Trait que va aquí adentro
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'price_per_hour',
        'description',
        'sport_id',
        'district_id',
        'user_id',
        'lat', 'lng',
        // NUEVOS CAMPOS:
        'open_time',
        'close_time',
    ];

    // --- RELACIONES ---

    /**
     * Relación: Una cancha pertenece a un dueño (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relación: Una cancha tiene un deporte (Sport).
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class); 
    }

    /**
     * Relación: Una cancha está en un distrito (District).
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relación: Una cancha tiene muchas reservas.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
    
    // --- GESTIÓN DE MEDIA (SPATIE) ---
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('canchas')
             ->singleFile(false); 
    }
}