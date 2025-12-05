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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cancha extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Usamos guarded vacío para permitir asignación masiva en todos los campos.
     * Es más práctico que fillable cuando tienes muchos campos.
     */
    protected $guarded = [];

    /**
     * Los casts aseguran que los datos tengan el tipo correcto al salir de la BD.
     */
    protected $casts = [
        'price_per_hour' => 'decimal:2', // Siempre con 2 decimales
        'lat' => 'double',               // Coordenadas como números, no strings
        'lng' => 'double',
        'user_id' => 'integer',
        'district_id' => 'integer',
        // 'open_time' y 'close_time' se pueden castear a 'datetime' si fuera necesario
    ];

    // --- RELACIONES ---

    /**
     * Relación: El dueño de la cancha.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🟢 SPRINT 7: Multideporte
     * Relación Muchos a Muchos con Sport.
     * Requiere la tabla pivote 'cancha_sport'.
     */
    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class, 'cancha_sport')
                    ->withTimestamps(); // Para guardar created_at/updated_at en la tabla pivote
    }

    /**
     * Relación: Distrito donde se ubica.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relación: Reservas asociadas.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    // --- GESTIÓN DE MEDIA (SPATIE) ---

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('canchas')
             ->singleFile(false); // Permite múltiples fotos
    }

    // --- ACCESORS (AYUDAS EXTRA) ---

    /**
     * Ayuda para obtener la URL de la primera imagen o null si no hay.
     * Uso en Blade: $cancha->primary_image_url
     */
    public function getPrimaryImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('canchas');
    }
}