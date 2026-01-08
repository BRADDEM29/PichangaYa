<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
        'is_active', 
        'user_id',
        'district_id',
        'lat',
        'lng',
        'open_time',
        'close_time',
        'contact_phone',
        'slug',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'lat'            => 'double',
        'lng'            => 'double',
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($cancha) {
            if (empty($cancha->slug)) {
                $cancha->slug = Str::slug($cancha->name) . '-' . substr(uniqid(), -4);
            }
        });
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class, 'cancha_sport');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

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
     * Conversión automática
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // ✅ SOLO MANTENEMOS THUMB (Para tarjetas y listados)
        // La versión 'large' la eliminamos porque el "original" ya es un WebP optimizado
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->format('webp')
            ->nonQueued(); 
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'cancha_service');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'cancha_id', 'user_id')->withTimestamps();
    }

    public function isFavoritedBy($user) 
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}