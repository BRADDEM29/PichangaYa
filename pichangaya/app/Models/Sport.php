<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    // 🟢 AGREGAMOS 'total_players'
    protected $fillable = ['name', 'icon', 'total_players'];

    public function lobbies()
    {
        return $this->hasMany(Lobby::class);
    }
}