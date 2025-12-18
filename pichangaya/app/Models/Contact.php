<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // ESTA ES LA LÍNEA QUE DEBES AGREGAR:
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];
}