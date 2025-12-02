<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancha_id',
        'user_id',
        'start_time',
        'end_time',
        'total_price',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * La reserva pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        // Asumiendo que tu modelo User está en App\Models\User
        return $this->belongsTo(User::class);
    }

    /**
     * La reserva pertenece a una cancha.
     */
    public function cancha(): BelongsTo
    {
        // El modelo Cancha ya está definido en tu proyecto
        return $this->belongsTo(Cancha::class);
    }
}