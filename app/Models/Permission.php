<?php
// app/Models/Permission.php
// Puedes generar este modelo con: php artisan make:model Permission

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rol;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions'; // Asumimos que la tabla se llama 'permissions'
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Los roles que tienen este permiso.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'permission_role',
            'permission_id',
            'role_id'
        );
    }
}
