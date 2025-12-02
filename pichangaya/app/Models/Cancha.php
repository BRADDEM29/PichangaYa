<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'sport_id',
        'district_id',
        // Opcional, si tienes coordenadas para el Sprint 7:
        // 'latitude', 
        // 'longitude', 
    ];

    // --- RELACIONES DE LA CANCHA ---

    /**
     * Relación: Una cancha pertenece a un dueño (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relación: Una cancha tiene un deporte (Sport) asignado.
     */
    public function sport(): BelongsTo
    {
        // Se puede omitir el segundo argumento ('sport_id') si se sigue la convención de nombres (sport_id)
        return $this->belongsTo(Sport::class); 
    }

    /**
     * Relación: Una cancha está ubicada en un distrito (District).
     */
    public function district(): BelongsTo
    {
        // Se puede omitir el segundo argumento ('district_id') si se sigue la convención de nombres (district_id)
        return $this->belongsTo(District::class);
    }

    /**
     * NUEVA RELACIÓN PARA SPRINT 6: Una cancha puede tener muchas reservas.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
    
    // --- GESTIÓN DE MEDIA (SPATIE) ---
    
    /**
     * Configuración de las colecciones de media.
     * 🔴 Configurada para Múltiples Imágenes (colección 'canchas').
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('canchas')
             ->singleFile(false); // Aseguramos que se permitan múltiples archivos
    }

    // --- AYUDAS Y SCOPES (OPCIONALES) ---

    /**
     * Scope local para canchas activas o disponibles si añades un campo 'is_active'.
     * public function scopeActive($query)
     * {
     * return $query->where('is_active', true);
     * }
     */
}