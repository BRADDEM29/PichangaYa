<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Models\Suggestion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'rating',
        'comment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}