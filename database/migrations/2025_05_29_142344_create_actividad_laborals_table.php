<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_laboral', function (Blueprint $table) {
            $table->id('id_act_lab');
            $table->string('nombre_actividad', 255);
            $table->string('direccion_trabajo', 255)->nullable();
            $table->string('horario')->nullable();  // Columna faltante en la migración
            $table->string('horas_x_dia')->nullable();
            $table->string('rem_men_aprox')->nullable();  // Nombre diferente en el modelo
            $table->string('telefono', 20)->nullable();   // Nombre diferente en el modelo
            $table->unsignedBigInteger('id_adulto');
            $table->timestamps();

            $table->foreign('id_adulto')
                  ->references('id_adulto')
                  ->on('adulto_mayor')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_laboral');  // Corregido el nombre de la tabla
    }
};