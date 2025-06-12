<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('persona_juridica', function (Blueprint $table) {
            $table->id('id_juridica');
            $table->unsignedBigInteger('id_encargado');
            $table->string('nombre_institucion', 255);
            $table->string('direccion', 255);
            $table->string('telefono', 20);
            $table->string('nombre_funcionario', 255);

            $table->foreign('id_encargado')->references('id_encargado')->on('encargado')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_juridica');
    }
};
