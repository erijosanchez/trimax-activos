<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'is_active',
        'last_login_at',
        'phone',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // Roles disponibles
    const ROLE_ADMIN = 'admin';
    const ROLE_TI = 'ti';
    const ROLE_SERVICIOS_GENERALES = 'servicios_generales';
    const ROLE_MARKETING = 'marketing';
    const ROLE_VIEWER = 'viewer';

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Verificar si puede gestionar inventario
     */
    public function canManageInventory(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_TI,
            self::ROLE_SERVICIOS_GENERALES,
            self::ROLE_MARKETING
        ]);
    }

    /**
     * Verificar si puede realizar mantenimientos
     */
    public function canPerformMaintenance(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_TI
        ]);
    }

    /**
     * Mantenimientos realizados como técnico
     */
    public function maintenancesAsTechnician()
    {
        return $this->hasMany(Maintenance::class, 'technician_id');
    }

    /**
     * Mantenimientos recibidos como usuario
     */
    public function maintenancesAsUser()
    {
        return $this->hasMany(Maintenance::class, 'user_id');
    }

    /**
     * Obtener nombre del rol para mostrar
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_TI => 'TI',
            self::ROLE_SERVICIOS_GENERALES => 'Servicios Generales',
            self::ROLE_MARKETING => 'Marketing',
            self::ROLE_VIEWER => 'Visualizador',
            default => 'Sin Rol',
        };
    }
}
