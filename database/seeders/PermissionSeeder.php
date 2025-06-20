<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Para evitar duplicados, solo insertaremos si no existen.
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'description' => 'Ver el dashboard principal'],

            // Gestión de Roles (La clave para tu problema)
            ['name' => 'roles.view', 'description' => 'Ver lista de roles'],
            ['name' => 'roles.create', 'description' => 'Crear nuevos roles'],
            ['name' => 'roles.edit', 'description' => 'Editar roles existentes'],
            ['name' => 'roles.delete', 'description' => 'Eliminar roles'],

            // Gestión de Usuarios
            ['name' => 'users.view', 'description' => 'Ver lista de usuarios'],
            ['name' => 'users.create', 'description' => 'Crear nuevos usuarios (registrar personal)'],
            ['name' => 'users.edit', 'description' => 'Editar usuarios existentes'],
            ['name' => 'users.delete', 'description' => 'Eliminar usuarios'],
            ['name' => 'users.toggle_activity', 'description' => 'Activar/Desactivar usuarios'],

            // Gestión de Pacientes (Adulto Mayor)
            ['name' => 'adulto_mayor.view', 'description' => 'Ver lista de pacientes'],
            ['name' => 'adulto_mayor.create', 'description' => 'Registrar nuevos pacientes'],
            ['name' => 'adulto_mayor.edit', 'description' => 'Editar pacientes existentes'],
            ['name' => 'adulto_mayor.delete', 'description' => 'Eliminar pacientes'],

            // Módulo de Protección (Legal)
            ['name' => 'proteccion.view', 'description' => 'Ver casos de protección'],
            ['name' => 'proteccion.create', 'description' => 'Registrar nuevos casos de protección'],
            ['name' => 'proteccion.edit', 'description' => 'Editar casos de protección'],
            ['name' => 'proteccion.delete', 'description' => 'Eliminar casos de protección'],
            ['name' => 'proteccion.reportes', 'description' => 'Ver reportes de protección'],

            // Módulo de Responsable de Salud (Enfermería, Fisio, etc.)
            ['name' => 'salud.view', 'description' => 'Ver módulos de salud'],
            ['name' => 'salud.servicios', 'description' => 'Acceder a servicios médicos'],
            ['name' => 'salud.historias', 'description' => 'Acceder a historias clínicas'],
            ['name' => 'salud.reportes', 'description' => 'Ver reportes de salud'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        $this->command->info(count($permissions) . ' permisos han sido creados o verificados exitosamente.');
    }
}
