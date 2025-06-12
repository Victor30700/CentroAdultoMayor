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
        Schema::create('persona_natural', function (Blueprint $table) {
            $table->id('id_natural');
            $table->unsignedBigInteger('id_encargado');
            $table->string('primer_apellido', 100);
            $table->string('segundo_apellido', 100)->nullable();
            $table->string('nombres', 255);
            $table->integer('edad');
            $table->string('ci', 20);
            $table->string('telefono', 20)->nullable();
            $table->string('direccion_domicilio', 255)->nullable();
            $table->string('relacion_parentesco', 100)->nullable();
            $table->string('direccion_de_trabajo', 255)->nullable();
            $table->string('ocupacion', 100)->nullable();

            $table->foreign('id_encargado')->references('id_encargado')->on('encargado')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_natural');
    }
};
