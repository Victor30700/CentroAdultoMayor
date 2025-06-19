<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            // Solo añadir la columna si no existe ya
            if (!Schema::hasColumn('persona', 'area_especialidad_legal')) {
                // Añade la columna 'area_especialidad_legal' que falta en tu base de datos.
                // La define como nullable para evitar problemas con registros existentes que no la tienen.
                // El default se asegura que los registros viejos tengan un valor válido.
                $table->enum('area_especialidad_legal', ['Asistente Social', 'Psicologia', 'Derecho'])
                      ->nullable()
                      ->default(null)
                      ->after('area_especialidad'); // La coloca después de la otra columna de especialidad.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            // Código para revertir el cambio si es necesario
            if (Schema::hasColumn('persona', 'area_especialidad_legal')) {
                $table->dropColumn('area_especialidad_legal');
            }
        });
    }
};
