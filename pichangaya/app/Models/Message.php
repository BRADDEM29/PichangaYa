<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'party_id',     // Para chat de grupo
        'lobby_id',     // Para chat de sala
        'content', 
        'type',         // 'text', 'system', 'invite'
        'read_at'
    ];

    // --- RELACIONES DE USUARIOS ---

    // El usuario que envía el mensaje
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // El usuario que recibe (Solo para Chat Privado 1 a 1)
    // Importante tenerlo para que no falle el historial de chats privados
    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // --- RELACIONES DE CONTEXTO (Nuevas) ---

    // Si el mensaje pertenece a un Grupo (Party)
    public function party() {
        return $this->belongsTo(Party::class);
    }

    // Si el mensaje pertenece a una Sala de Espera (Lobby)
    public function lobby() {
        return $this->belongsTo(Lobby::class);
    }
}