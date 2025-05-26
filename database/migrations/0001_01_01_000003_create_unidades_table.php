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
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('placa')->nullable();
            $table->string('marca')->nullable();
            $table->integer('capacidad')->default(1);
            $table->string('estado')->default('activa');
            $table->integer('personas_por_unidad')->default(1);
            $table->boolean('tiene_mascotas')->default(false);
            $table->integer('vehiculos')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
