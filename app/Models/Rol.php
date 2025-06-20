<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $incrementing = true;
    public $timestamps = false; // Tu tabla 'rol' no tiene timestamps

    protected $fillable = [
        'nombre_rol',
        'descripcion',
        'active',
    ];

    /**
     * Los usuarios que pertenecen a este rol.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }

    /**
     * Los permisos asignados a este rol.
     * CORRECCIÓN VITAL: Se añaden los dos últimos parámetros a la relación belongsToMany.
     * - 'id_rol': Es la clave primaria de este modelo (Rol).
     * - 'id': Es la clave primaria del modelo relacionado (Permission).
     * Esto es absolutamente necesario porque la clave primaria de Rol no es 'id'.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role', // Tabla pivote
            'role_id',         // Clave foránea de Rol en la tabla pivote
            'permission_id',   // Clave foránea de Permission en la tabla pivote
            'id_rol',          // Clave primaria de este modelo (Rol)
            'id'               // Clave primaria del modelo relacionado (Permission)
        );
    }

    /**
     * Verifica si el rol tiene un permiso específico.
     */
    public function hasPermissionTo(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
