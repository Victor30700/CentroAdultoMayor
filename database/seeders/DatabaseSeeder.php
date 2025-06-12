<?php
// database/seeders/DatabaseSeeder.php
// Actualiza tu DatabaseSeeder para incluir los nuevos seeders en el orden correcto.

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Tu migración de Rol ya inserta los roles, así que no necesitas un RolSeeder separado
            // a menos que quieras mover esa lógica a un seeder.
            // Si tu migración de Rol ya los crea, está bien.
            // Si moviste la creación de roles a un RolSeeder, llámalo aquí primero.
            // Ejemplo: RolSeeder::class, 
            
            AdminUserSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
?>