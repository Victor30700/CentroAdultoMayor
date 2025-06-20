<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

// Se importa solo los modelos necesarios para las relaciones directas.
use App\Models\Persona;
use App\Models\Rol;
// Permission ya no se necesita aquí porque la relación es a través del Rol.

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
     * Permite hacer Auth::user()->persona->...
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'ci', 'ci');
    }

    /**
     * Relación con la tabla Rol.
     * Permite hacer Auth::user()->rol->nombre_rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    /**
     * [SOLUCIÓN DEFINITIVA]
     * Verifica si el usuario tiene un permiso específico a través de su rol.
     * Esta es la forma correcta y eficiente de manejar los permisos.
     *
     * @param string $permissionName El nombre del permiso a verificar (e.g., 'roles.create').
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        // Si el usuario no tiene un rol asignado, no puede tener permisos.
        if (!$this->rol) {
            return false;
        }

        // Carga los permisos del rol solo si no han sido cargados previamente (Eager Loading).
        // Esto optimiza las consultas a la base de datos, evitando el problema N+1.
        $this->rol->loadMissing('permissions');

        // Verifica si la colección de permisos del rol contiene el permiso que buscamos.
        return $this->rol->permissions->contains('name', $permissionName);
    }

    /**
     * [SOLUCIÓN DEFINITIVA]
     * Devuelve true si el usuario tiene el rol cuyo nombre es $roleName.
     * Es insensible a mayúsculas/minúsculas.
     */
    public function hasRole(string $roleName): bool
    {
        return strtolower(optional($this->rol)->nombre_rol) === strtolower($roleName);
    }

    //
    // --- SECCIÓN DE MANEJO DE LOGIN (SIN CAMBIOS) ---
    // Toda tu lógica original se mantiene intacta.
    //

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

    //
    // --- SECCIÓN DE SCOPES Y ATRIBUTOS (SIN CAMBIOS) ---
    // Toda tu lógica original se mantiene intacta.
    //

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

        return "Usuario CI: {$this->ci}";
    }
}