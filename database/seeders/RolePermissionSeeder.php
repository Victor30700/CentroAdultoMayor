<?php
// database/seeders/RolePermissionSeeder.php
// Puedes generar este seeder con: php artisan make:seeder RolePermissionSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles y permisos. Asegúrate que los roles y permisos ya existan (ejecuta sus seeders primero)
        $adminRole = Rol::where('nombre_rol', 'admin')->first();
        $responsableRole = Rol::where('nombre_rol', 'responsable')->first();
        // Añade otros roles si es necesario
        // $legalRole = Rol::where('nombre_rol', 'legal')->first();
        // $asistenteSocialRole = Rol::where('nombre_rol', 'asistente-social')->first();

        if (!$adminRole || !$responsableRole) {
            $this->command->error('No se encontraron los roles "admin" o "responsable". Asegúrate de ejecutar el seeder de roles primero.');
            return;
        }

        // Permisos para el rol de Administrador
        $adminPermissions = Permission::pluck('id')->toArray(); // Todos los permisos
        if ($adminRole) {
            $adminRole->permissions()->sync($adminPermissions); // sync() es útil para evitar duplicados
            $this->command->info('Permisos asignados al rol de Administrador.');
        }


        // Permisos para el rol de Responsable de Salud
        $responsablePermissionsNames = [
            'dashboard.view',
            'adulto_mayor.view',
            'adulto_mayor.create',
        ];
        $responsablePermissions = Permission::whereIn('name', $responsablePermissionsNames)->pluck('id');
        if ($responsableRole) {
            $responsableRole->permissions()->sync($responsablePermissions);
            $this->command->info('Permisos asignados al rol de Responsable de Salud.');
        }

        // Aquí puedes asignar permisos a otros roles (legal, asistente-social) si los defines
        // Ejemplo para rol 'legal'
        /*
        if ($legalRole) {
            $legalPermissionsNames = [
                'dashboard.view',
                'adulto_mayor.view', // Quizás solo ver, no crear
                'usuario_legal.view',
                'usuario_legal.create',
            ];
            $legalPermissions = Permission::whereIn('name', $legalPermissionsNames)->pluck('id');
            $legalRole->permissions()->sync($legalPermissions);
            $this->command->info('Permisos asignados al rol Legal.');
        }
        */
    }
}
?>