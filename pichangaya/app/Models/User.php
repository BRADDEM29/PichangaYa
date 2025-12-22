<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Models\User.php
namespace App\Models;

// Importaciones de Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

// Importaciones de Eloquent para relaciones
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
}