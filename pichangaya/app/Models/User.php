<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Models\User.php

namespace App\Models;

// Importaciones de Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
// 🟢 1. IMPORTANTE: Importamos SoftDeletes
use Illuminate\Database\Eloquent\SoftDeletes; 

// Importaciones de Eloquent para relaciones
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    // 🟢 2. IMPORTANTE: Usamos el Trait dentro de la clase
    use SoftDeletes; 
    
    /**
     * Los atributos que son asignables en masa (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'profile_photo_path',
        'phone',
        'consecutive_cancellations', 
        'is_blocked',
    ];

    /**
     * Los atributos que deberían ser ocultados para serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Los accesores a anexar a la forma de array del modelo.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Obtiene los atributos que deben ser casteados.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Un usuario puede tener muchas reservas.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
    
    /**
     * Un usuario con rol 'owner' puede tener múltiples canchas.
     */
    public function canchas(): HasMany
    {
        return $this->hasMany(Cancha::class);
    }

    /**
     * Relación con teléfonos secundarios.
     */
    public function secondaryPhones(): HasMany
    {
        return $this->hasMany(UserPhone::class);
    }

    // ============================================================
    // MÉTODOS DE AYUDA PARA ROLES
    // ============================================================

    /**
     * Método genérico para verificar el rol.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    /**
     * Verifica si el usuario es un dueño de cancha (owner).
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }
    
    /**
     * Verifica si el usuario es un administrador (admin).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verifica si el usuario es un cliente normal (user).
     * Fundamental para la lógica del Modo Oscuro.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // app/Models/User.php

    public function favorites()
    {
        // Asumiendo que tu tabla pivote se llama 'cancha_user' o similar
        // Si seguiste convenciones de Laravel para una relación Many-to-Many:
        return $this->belongsToMany(Cancha::class, 'favorites', 'user_id', 'cancha_id')->withTimestamps();
        
        // OJO: Si tu tabla pivote se llama diferente (ej. 'cancha_user'), usa:
        // return $this->belongsToMany(Cancha::class, 'cancha_user');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    /*
    |--------------------------------------------------------------------------
    | MÓDULO PICHANGAYA ARENA (RELACIONES)
    |--------------------------------------------------------------------------
    */

    // 1. El equipo del que soy Capitán (Dueño)
    public function captainTeam()
    {
        return $this->hasOne(Team::class, 'captain_id');
    }

    // 2. El equipo al que pertenezco (Miembro)
    public function teamMember()
    {
        return $this->hasOne(TeamMember::class, 'user_id');
    }

    // Helper: Obtener mi equipo actual (ya sea como capitán o miembro)
    public function currentTeamArena()
    {
        if ($this->captainTeam) {
            return $this->captainTeam;
        }
        if ($this->teamMember) {
            return $this->teamMember->team;
        }
        return null;
    }

    // 3. Sistema de Amigos (Enviados y Recibidos)
    public function sentFriendships()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    public function receivedFriendships()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    // Helper: Obtener lista de amigos aceptados
    public function getFriendsAttribute()
    {
        $sent = $this->sentFriendships()->where('status', 'accepted')->with('friend')->get()->pluck('friend');
        $received = $this->receivedFriendships()->where('status', 'accepted')->with('user')->get()->pluck('user');
        
        return $sent->merge($received);
    }

    // 4. Lobby (Sala de espera actual)
    public function currentLobbySlot()
    {
        return $this->hasOne(LobbySlot::class, 'user_id');
    }
}