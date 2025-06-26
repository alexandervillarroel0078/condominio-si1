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
        Schema::create('reclamos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['sugerencia', 'reclamo'])->default('reclamo');
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->string('adjunto')->nullable();
            $table->dateTime('fechaCreacion')->useCurrent();
            $table->enum('estado', ['pendiente', 'abierto', 'resuelto'])->default('pendiente');
            $table->text('respuesta')->nullable();

            // Foreign keys
            $table->foreignId('residente_id')->nullable()
                ->constrained('residentes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('empleado_id')->nullable()
                ->constrained('empleados')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamos');
    }
};
