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
        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->enum('tipo', ['Urgente', 'Informativo'])->default('Informativo');
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->boolean('notificado')->default(false); // ❌ Quitado el ->after()
            $table->unsignedBigInteger('usuario_id'); // directiva
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};

