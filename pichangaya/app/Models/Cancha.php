<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Models\Cancha.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Importaciones de Spatie
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
// Importaciones de Relaciones
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 
use Illuminate\Support\Str;

class Cancha extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'price_per_hour',
        'description',
        'is_featured', 
        'is_active', // 🟢 AGREGADO: Para ocultar la cancha si el dueño cambia de rol
        'user_id',
        // 'sport_id', 
        'district_id',
        'lat',
        'lng',
        'open_time',
        'close_time',
        'contact_phone',
        'slug',
    ];

    /**
     * Casts para asegurar tipos de datos correctos.
     */
    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'lat'            => 'double',
        'lng'            => 'double',
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean', // 🟢 AGREGADO: Asegura que se trate como true/false
    ];

    // --- RELACIONES ---

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * BOOT: Generar el slug automáticamente al crear o guardar.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($cancha) {
            // Crea el slug basado en el nombre si no existe o si cambia el nombre
            if (empty($cancha->slug)) {
                $cancha->slug = Str::slug($cancha->name) . '-' . substr(uniqid(), -4);
            }
        });
    }
    
    /**
     * Relación: Una cancha pertenece a un dueño (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relación: Muchos a Muchos con Deportes (Multideporte)
     */
    public function sports(): BelongsToMany
    {
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
        $this->addMediaCollection('canchas'); 
    }

    /**
     * Conversión automática a WebP y Redimensionado
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // 1. Conversión 'thumb': Para tarjetas de listado
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->format('webp')   // Convierte a WebP
            ->nonQueued();     // Procesa al instante

        // 2. Conversión 'large': Para el detalle y carrusel
        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->format('webp')   // Convierte a WebP
            ->nonQueued();
    }

    /**
     * Relación: Una cancha tiene muchos servicios.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'cancha_service');
    }

    /**
     * 🟢 ESTA ES LA FUNCIÓN QUE TE FALTABA
     * Relación muchos a muchos con usuarios (Favoritos)
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'cancha_id', 'user_id')->withTimestamps();
    }

    public function isFavoritedBy($user) 
    {
        if (!$user) return false;
        // Ahora sí existe el método favorites()
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}