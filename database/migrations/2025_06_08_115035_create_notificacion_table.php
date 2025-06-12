<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionTable extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('contenido');
            $table->dateTime('fecha_hora');
            $table->enum('tipo', ['Urgente', 'Informativa', 'Recordatorio']);
            $table->string('titulo');
            $table->foreignId('residente_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->boolean('leida')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notificacions');
    }
}
