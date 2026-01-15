<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name'];

    /**
     * Relación: Un distrito tiene muchos lobbies.
     */
    public function lobbies()
    {
        return $this->hasMany(Lobby::class);
    }
}