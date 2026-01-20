<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // 🟢 ACTUALIZADO: Agregamos los campos nuevos al fillable
    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'party_id',     // Para chat de grupo
        'lobby_id',     // Para chat de sala
        'content', 
        'type',         // 'text', 'system', 'invite'
        'read_at'
    ];

    // Relación: Quién envió
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relación: Contexto Party
    public function party() {
        return $this->belongsTo(Party::class);
    }

    // Relación: Contexto Lobby
    public function lobby() {
        return $this->belongsTo(Lobby::class);
    }
}