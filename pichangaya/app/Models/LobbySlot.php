<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LobbySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lobby_id', 'user_id', 'team_side', 'is_captain', 'confirmed_at'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }
}