<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = ['leader_id', 'invite_code'];

    // Relación: Miembros del grupo
    public function members()
    {
        return $this->hasMany(User::class, 'party_id');
    }

    // Relación: El Líder
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Relación: Chat del grupo
    public function messages()
    {
        return $this->hasMany(Message::class, 'party_id');
    }
}