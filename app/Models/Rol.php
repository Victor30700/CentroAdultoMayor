<?php
// app/Models/Rol.php
// Si no tienes este modelo, créalo con: php artisan make:model Rol
// Asegúrate que el nombre de la tabla y la clave primaria coincidan con tu migración.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol'; // Especifica el nombre de la tabla si no sigue la convención de Laravel (roles)
    protected $primaryKey = 'id_rol'; // Especifica la clave primaria si no es 'id'
    public $incrementing = true; // Si tu PK es auto-incremental. En tu migración es `id('id_rol')` lo que implica auto-incremental.

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
        // El segundo argumento es la clave foránea en la tabla 'usuario' que referencia a 'rol'.
        // El tercer argumento es la clave local en la tabla 'rol' (id_rol).
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }

    /**
     * Los permisos asignados a este rol.
     */
    public function permissions()
    {
        // El segundo argumento es el nombre de la tabla pivote.
        // El tercer argumento es la clave foránea de Rol en la tabla pivote.
        // El cuarto argumento es la clave foránea de Permission en la tabla pivote.
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Verifica si el rol tiene un permiso específico.
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermissionTo(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
?>