<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ================================
        // 1) ADMINISTRADOR (CI: 12345678)
        // ================================
        $ciAdmin = '12345678';

        // Insertar persona ADMIN solo si no existe
        if (! DB::table('persona')->where('ci', $ciAdmin)->exists()) {
            DB::table('persona')->insert([
                'ci'               => $ciAdmin,
                'primer_apellido'  => 'Admin',
                'segundo_apellido' => 'Sistema',
                'nombres'          => 'Administrador',
                'sexo'             => 'M',
                'fecha_nacimiento' => '1980-01-01',
                'edad'             => 44,
                'estado_civil'     => 'soltero',
                'domicilio'        => 'Dirección administrativa',
                'telefono'         => '12345678',
                'zona_comunidad'   => 'Centro',
                // Agregar obligatoriamente el campo NOT NULL:
                'area_especialidad'=> 'Enfermeria',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // Insertar usuario ADMIN solo si no existe
        if (! DB::table('usuario')->where('ci', $ciAdmin)->exists()) {
            User::create([
                'ci'             => $ciAdmin,
                'id_rol'         => 1, // admin
                'password'       => Hash::make('admin123'),
                'active'         => true,
                'login_attempts' => 0,
            ]);
        }

        // ================================
        // 2) RESPONSABLE (CI: 87654321)
        // ================================
        $ciResp = '87654321';

        if (! DB::table('persona')->where('ci', $ciResp)->exists()) {
            DB::table('persona')->insert([
                'ci'               => $ciResp,
                'primer_apellido'  => 'González',
                'segundo_apellido' => 'Pérez',
                'nombres'          => 'María Elena',
                'sexo'             => 'F',
                'fecha_nacimiento' => '1985-05-15',
                'edad'             => 39,
                'estado_civil'     => 'casado',
                'domicilio'        => 'Av. Principal 123',
                'telefono'         => '87654321',
                'zona_comunidad'   => 'Norte',
                // Aquí defines su especialidad, p. ej. 'Fisioterapia'
                'area_especialidad'=> 'Fisioterapia',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        if (! DB::table('usuario')->where('ci', $ciResp)->exists()) {
            User::create([
                'ci'             => $ciResp,
                'id_rol'         => 2, // responsable
                'password'       => Hash::make('responsable123'),
                'active'         => true,
                'login_attempts' => 0,
            ]);
        }

        // ================================
        // 3) LEGAL (CI: 87654350)
        // ================================
        $ciLegal = '87654350';

        if (! DB::table('persona')->where('ci', $ciLegal)->exists()) {
            DB::table('persona')->insert([
                'ci'               => $ciLegal,
                'primer_apellido'  => 'Salazar',
                'segundo_apellido' => 'Lopez',
                'nombres'          => 'Carlos Andrés',
                'sexo'             => 'M',
                'fecha_nacimiento' => '1990-10-20',
                'edad'             => 33,
                'estado_civil'     => 'soltero',
                'domicilio'        => 'Av. Legalista 456',
                'telefono'         => '87654350',
                'zona_comunidad'   => 'Sur',
                // Para que no rompa NOT NULL, le asignamos algo (aunque no uses esta especialidad para legal)
                'area_especialidad'=> 'Kinesiologia',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        if (! DB::table('usuario')->where('ci', $ciLegal)->exists()) {
            User::create([
                'ci'             => $ciLegal,
                'id_rol'         => 3, // legal
                'password'       => Hash::make('legal123'),
                'active'         => true,
                'login_attempts' => 0,
            ]);
        }

        // ====================================
        // 4) ASISTENTE SOCIAL (CI: 87654359)
        // ====================================
        $ciAsis = '87654359';

        if (! DB::table('persona')->where('ci', $ciAsis)->exists()) {
            DB::table('persona')->insert([
                'ci'               => $ciAsis,
                'primer_apellido'  => 'Mamani',
                'segundo_apellido' => 'Quispe',
                'nombres'          => 'Ana Lucía',
                'sexo'             => 'F',
                'fecha_nacimiento' => '1992-08-12',
                'edad'             => 31,
                'estado_civil'     => 'casado',
                'domicilio'        => 'Barrio Solidaridad 789',
                'telefono'         => '87654359',
                'zona_comunidad'   => 'Este',
                // Para que no rompa NOT NULL
                'area_especialidad'=> 'Enfermeria',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        if (! DB::table('usuario')->where('ci', $ciAsis)->exists()) {
            User::create([
                'ci'             => $ciAsis,
                'id_rol'         => 4, // asistente-social
                'password'       => Hash::make('asistentesocial123'),
                'active'         => true,
                'login_attempts' => 0,
            ]);
        }

        // Mensajes en consola
        $this->command->info('Usuarios creados exitosamente:');
        $this->command->info('Admin - CI: 12345678, Password: admin123');
        $this->command->info('Responsable - CI: 87654321, Password: responsable123');
        $this->command->info('Legal - CI: 87654350, Password: legal123');
        $this->command->info('Asistente Social - CI: 87654359, Password: asistentesocial123');
    }
}
