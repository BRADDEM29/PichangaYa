<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Models\UserPhone.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    protected $fillable = ['user_id', 'phone_number', 'label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}