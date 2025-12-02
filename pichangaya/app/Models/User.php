<?php

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
use App\Models\Reserva; // Aseguramos que el modelo Reserva esté disponible

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
     * Se ha verificado que 'role' esté en $fillable, ya que es fundamental para
     * la lógica de tu aplicación (admin, owner, user).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'profile_photo_path', 
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
            // El campo two_factor_confirmed_at se castea automáticamente por el trait.
        ];
    }
    
    // --- RELACIONES PARA SPRINT 6 ---

    /**
     * NUEVA RELACIÓN: Un usuario puede tener muchas reservas.
     * Esto aplica para el rol 'user' (el que alquila) y 'owner' (el que recibe la reserva).
     * Nota: Si un 'owner' también es el que gestiona la cancha, esta es la relación de las reservas que hizo como cliente.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
    
    // --- RELACIONES ADICIONALES ---

    /**
     * Un usuario con rol 'owner' puede tener múltiples canchas.
     * Es crucial para que los dueños administren sus canchas.
     */
    public function canchas(): HasMany
    {
        return $this->hasMany(Cancha::class);
    }

    // --- AYUDAS Y ROLES ---

    /**
     * Método de ayuda para verificar el rol.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    /**
     * Verifica si el usuario es un dueño de cancha.
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }
    
    /**
     * Verifica si el usuario es un administrador.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}