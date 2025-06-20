<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

// IMPORTAR los modelos Persona, Rol y Permission para las relaciones
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Permission;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ci',
        'id_rol',
        'password',
        'active',
        'login_attempts',
        'last_failed_login_at',
        'temporary_lockout_until',
        'created_at',
        'updated_at'
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
    ];

    /**
     * Relación con la tabla Persona (usando CI como clave foránea).
     * Permite hacer Auth::user()->persona->area_especialidad
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'ci', 'ci');
    }

    /**
     * Relación con la tabla Rol.
     * Permite hacer Auth::user()->rol->id_rol y Auth::user()->rol->nombre_rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Métodos para manejo de intentos de login
    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        $this->last_failed_login_at = Carbon::now();

        // Si alcanza 3 intentos, bloquear temporalmente (sin desactivar)
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
        // No puede loguearse si está inactivo
        if (!$this->active) {
            return false;
        }

        // No puede loguearse si está temporalmente bloqueado
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

    // Scope para usuarios activos
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Scope para usuarios bloqueados temporalmente
    public function scopeTemporarilyLocked($query)
    {
        return $query->where('active', false)
                     ->whereNotNull('temporary_lockout_until')
                     ->where('temporary_lockout_until', '>', Carbon::now());
    }

    // Obtener nombre completo del usuario (temporalmente usando CI)
    public function getFullNameAttribute()
    {
        // Si existe la relación con Persona, devolvemos nombres+apellidos
        if ($this->persona) {
            return trim("{$this->persona->nombres} {$this->persona->primer_apellido} {$this->persona->segundo_apellido}");
        }

        // Fallback mientras no exista persona registrada
        return "Usuario CI: {$this->ci}";
    }

    // Obtener nombre del rol en minúsculas
    public function getRoleNameAttribute()
    {
        if ($this->rol) {
            return strtolower($this->rol->nombre_rol);
        }

        // Fallback temporal en base a id_rol
        $roles = [
            1 => 'admin',
            2 => 'responsable',
            3 => 'legal',
        ];

        return $roles[$this->id_rol] ?? 'sin-rol';
    }

    /**
     * Relación many-to-many con Permission a través de permission_role.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',       // clave foránea de Rol en la tabla pivote
            'permission_id', // clave foránea de Permission en la tabla pivote
            'id_rol',        // PK de tabla rol
            'id'             // PK de tabla permissions
        );
    }

    /**
     * Devuelve true si el usuario tiene el rol cuyo nombre es $roleName.
     */
    public function hasRole(string $roleName): bool
    {
        return optional($this->rol)->nombre_rol === $roleName;
    }

    /**
     * Devuelve true si el usuario tiene el permiso $permName.
     * (Se asume que el permiso está vinculado al rol del usuario).
     */
    public function hasPermission(string $permName): bool
    {
        return $this->permissions
                    ->pluck('name')
                    ->contains($permName);
    }
}
