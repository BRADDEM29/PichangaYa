<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name', 'icon'];

    // Esta es la relación que permite acceder a los lobbies desde un deporte
    public function lobbies()
    {
        return $this->hasMany(Lobby::class);
    }
}