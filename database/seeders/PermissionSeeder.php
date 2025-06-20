<?php
// database/seeders/PermissionSeeder.php
// Puedes generar este seeder con: php artisan make:seeder PermissionSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vaciar la tabla primero si se ejecuta múltiples veces para evitar duplicados
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Desactivar revisión de FK temporalmente si es necesario
        // Permission::truncate(); // Cuidado con esto en producción si ya hay datos relacionados
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [
            // Permisos generales
            ['name' => 'dashboard.view', 'description' => 'Permite ver el dashboard principal de su rol.'],

            // Permisos Adulto Mayor
            ['name' => 'adulto_mayor.view', 'description' => 'Permite ver la lista o detalles de adultos mayores.'],
            ['name' => 'adulto_mayor.create', 'description' => 'Permite registrar un nuevo adulto mayor.'],
            ['name' => 'adulto_mayor.edit', 'description' => 'Permite editar la información de un adulto mayor existente.'],
            ['name' => 'adulto_mayor.delete', 'description' => 'Permite eliminar un adulto mayor.'],

            // Permisos Usuario Legal
            ['name' => 'usuario_legal.create', 'description' => 'Permite registrar un usuario legal.'],
            ['name' => 'usuario_legal.view', 'description' => 'Permite ver usuarios legales.'],
            // Podrías añadir .edit y .delete si es necesario

            // Permisos Responsable Salud (si son distintos a los de 'usuario')
            ['name' => 'responsable_salud.create', 'description' => 'Permite registrar un responsable de salud.'],
            ['name' => 'responsable_salud.view', 'description' => 'Permite ver responsables de salud.'],
            // Podrías añadir .edit y .delete si es necesario

            // Permisos para Gestionar Usuarios (tabla 'usuario')
            ['name' => 'users.view', 'description' => 'Permite ver la lista de todos los usuarios del sistema.'],
            ['name' => 'users.toggle_active', 'description' => 'Permite activar/desactivar usuarios.'],
            ['name' => 'users.edit_roles', 'description' => 'Permite modificar los roles de los usuarios.'],
            // Podrías añadir users.create, users.edit, users.delete si es necesario
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        $this->command->info(count($permissions) . ' permisos creados/verificados exitosamente.');
    }
}
?>