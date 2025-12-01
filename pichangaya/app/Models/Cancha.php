<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    
    // 🔴 Modificación para Múltiples Imágenes
    public function registerMediaCollections(): void
    {
        // Eliminamos el singleFile() para permitir muchas fotos
        $this->addMediaCollection('canchas'); 
    }
}