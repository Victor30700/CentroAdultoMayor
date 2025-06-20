<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\Persona;
use App\Models\Rol;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * CORRECCIÓN: Se alinea el `fillable` con las columnas de la migración 'create_usuario_table'.
     * Esto es crucial para que la asignación masiva (ej. al crear un usuario) funcione correctamente.
     */
    protected $fillable = [
        'id_persona',
        'id_rol',
        'nombre_usuario',
        'email',
        'password',
        'active',
        'login_attempts',
        'last_failed_login_at',
        'temporary_lockout_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'login_attempts' => 'integer',
        'last_failed_login_at' => 'datetime',
        'temporary_lockout_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime', // Se añade para seguir las convenciones de Laravel
    ];

    /**
     * CORRECCIÓN: La relación con Persona debe usar la clave foránea 'id_persona' como
     * se define en la migración. El segundo argumento es la clave foránea en la tabla `usuario`,
     * y el tercero es la clave primaria en la tabla `persona`.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    /**
     * Relación con la tabla Rol.
     * Esta relación ya era correcta.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    /**
     * Verifica si el usuario tiene un permiso específico a través de su rol.
     * Este método es correcto y se mantiene sin cambios.
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->rol) {
            return false;
        }
        $this->rol->loadMissing('permissions');
        return $this->rol->permissions->contains('name', $permissionName);
    }

    /**
     * Verifica si el usuario tiene un rol específico por su nombre.
     * Este método es correcto y se mantiene sin cambios.
     */
    public function hasRole(string $roleName): bool
    {
        return strtolower(optional($this->rol)->nombre_rol) === strtolower($roleName);
    }

    // --- SECCIÓN DE MANEJO DE LOGIN (SIN CAMBIOS) ---
    // Toda tu lógica original se mantiene intacta.

    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        $this->last_failed_login_at = Carbon::now();

        if ($this->login_attempts >= 3) {
            $this->temporary_lockout_until = Carbon::now()->addMinutes(10);
        }

        $this->save();
    }

    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->last_failed_login_at = null;
        $this->temporary_lockout_until = null;
        $this->save();
    }

    public function isTemporarilyLocked()
    {
        if ($this->temporary_lockout_until && Carbon::now()->lt($this->temporary_lockout_until)) {
            return true;
        }
        return false;
    }

    public function canLogin()
    {
        if (!$this->active) {
            return false;
        }

        if ($this->isTemporarilyLocked()) {
            return false;
        }

        return true;
    }

    public function getTimeUntilUnlock()
    {
        if ($this->temporary_lockout_until && Carbon::now()->lt($this->temporary_lockout_until)) {
            return Carbon::now()->diffInMinutes($this->temporary_lockout_until, false);
        }
        return 0;
    }

    // --- SECCIÓN DE SCOPES Y ATRIBUTOS (SIN CAMBIOS) ---
    // Toda tu lógica original se mantiene intacta.

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeTemporarilyLocked($query)
    {
        return $query->where('active', false)
                      ->whereNotNull('temporary_lockout_until')
                      ->where('temporary_lockout_until', '>', Carbon::now());
    }

    public function getFullNameAttribute()
    {
        if ($this->persona) {
            return trim("{$this->persona->nombres} {$this->persona->primer_apellido} {$this->persona->segundo_apellido}");
        }
        // Se ajusta el fallback en caso de que la persona no esté asociada.
        return $this->nombre_usuario ?? "Usuario #{$this->id_usuario}";
    }
}