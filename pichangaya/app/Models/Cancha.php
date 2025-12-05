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
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- NUEVA IMPORTACIÓN

class Cancha extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'price_per_hour',
        'description',
        'user_id',
        // 'sport_id', // Ya no lo guardamos aquí directo, pero si lo dejas no pasa nada.
        'district_id',
        'lat',
        'lng',
        'open_time',
        'close_time',
        'contact_phone',
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
     * 🟢 CAMBIO PRINCIPAL (Sprint 7 - Multideporte):
     * Ahora usamos belongsToMany para conectar con varios deportes.
     */
    public function sports(): BelongsToMany
    {
        // 'cancha_sport' es el nombre de la tabla intermedia que creamos en el Paso 1
        return $this->belongsToMany(Sport::class, 'cancha_sport');
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