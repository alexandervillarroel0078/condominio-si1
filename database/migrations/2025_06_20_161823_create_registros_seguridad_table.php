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
        Schema::create('registros_seguridad', function (Blueprint $table) {
            $table->id();
            
            // 👤 RELACIÓN CON USUARIO QUE REGISTRA
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Usuario que registra (Seguridad o Residente)');
            
            // 📋 INFORMACIÓN BÁSICA DEL REGISTRO
            $table->enum('tipo', ['ronda', 'incidente', 'reporte'])
                  ->comment('Tipo de registro de seguridad');
            
            $table->enum('origen', ['seguridad', 'residente'])
                  ->comment('Origen del registro');
            
            $table->timestamp('fecha_hora')
                  ->comment('Fecha y hora del evento');
            
            $table->string('ubicacion', 255)
                  ->comment('Ubicación donde ocurrió el evento');
            
            $table->text('descripcion')
                  ->comment('Descripción detallada del evento');
            
            // 🎯 CLASIFICACIÓN Y ESTADO
            $table->enum('prioridad', ['baja', 'media', 'alta'])
                  ->default('media')
                  ->comment('Prioridad del registro');
            
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelto'])
                  ->default('pendiente')
                  ->comment('Estado actual del registro');
            
            // 📝 INFORMACIÓN ADICIONAL
            $table->text('observaciones')
                  ->nullable()
                  ->comment('Observaciones adicionales');
            
            // 🔧 RESOLUCIÓN (SOLO PARA INCIDENTES)
            $table->foreignId('resuelto_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario que resolvió el incidente');
            
            $table->timestamp('fecha_resolucion')
                  ->nullable()
                  ->comment('Fecha y hora de resolución');
            
            // ⏰ TIMESTAMPS AUTOMÁTICOS
            $table->timestamps();
            
            // 📊 ÍNDICES PARA OPTIMIZACIÓN
            $table->index(['tipo', 'origen'], 'idx_tipo_origen');
            $table->index(['estado', 'prioridad'], 'idx_estado_prioridad');
            $table->index('fecha_hora', 'idx_fecha_hora');
            $table->index(['user_id', 'origen'], 'idx_usuario_origen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_seguridad');
    }
};