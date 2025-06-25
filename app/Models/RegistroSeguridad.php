<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RegistroSeguridad extends Model
{
    use HasFactory;

    protected $table = 'registros_seguridad';

    /**
     * Los atributos que pueden asignarse masivamente.
     */
    protected $fillable = [
        'user_id',           // Quién registra (Seguridad O Residente)
        'tipo',              // 'ronda', 'incidente', 'reporte'
        'origen',            // 'seguridad', 'residente'
        'fecha_hora',        // Cuándo pasó
        'ubicacion',         // Dónde pasó
        'descripcion',       // Qué pasó
        'prioridad',         // 'baja', 'media', 'alta'
        'estado',            // 'pendiente', 'en_revision', 'resuelto'
        'observaciones',     // Notas adicionales
        'resuelto_por',      // ID del Personal de Seguridad que resolvió
        'fecha_resolucion',  // Cuándo se resolvió
    ];

    /**
     * Conversiones de tipo para atributos.
     */
    protected $casts = [
        'fecha_hora' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    /**
     * Relación: Registro pertenece a un Usuario (quien lo creó)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Registro resuelto por un Usuario (Personal de Seguridad)
     */
    public function resueltoPor()
    {
        return $this->belongsTo(User::class, 'resuelto_por');
    }

    /**
     * Scope: Solo registros de seguridad (rondas, reportes, incidentes propios)
     */
    public function scopeDeSeguridad($query)
    {
        return $query->where('origen', 'seguridad');
    }

    /**
     * Scope: Solo reportes de residentes
     */
    public function scopeDeResidentes($query)
    {
        return $query->where('origen', 'residente');
    }

    /**
     * Scope: Solo incidentes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: Solo incidentes resueltos
     */
    public function scopeResueltos($query)
    {
        return $query->where('estado', 'resuelto');
    }

    /**
     * Scope: Por tipo específico
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: Por prioridad
     */
    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /**
     * Scope: Registros de hoy
     */
    public function scopeDeHoy($query)
    {
        return $query->whereDate('fecha_hora', Carbon::today());
    }

    /**
     * Scope: Registros de una semana
     */
    public function scopeUltimaSemana($query)
    {
        return $query->whereBetween('fecha_hora', [
            Carbon::now()->subWeek(),
            Carbon::now()
        ]);
    }

    /**
     * Accessor: Obtener el estado con formato
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => '🔴 Pendiente',
            'en_revision' => '🟡 En Revisión',
            'resuelto' => '🟢 Resuelto'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }
    /**
     * Método: Marcar como en revisión
     */
    public function marcarComoEnRevision($userId, $observaciones = null)
    {
        $this->update([
            'estado' => 'en_revision',
            'observaciones' => $observaciones ?: $this->observaciones,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Método: Verificar si puede ser marcado como en revisión
     */
    public function puedeMarcarseEnRevision()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Método: Verificar si está en revisión
     */
    public function estaEnRevision()
    {
        return $this->estado === 'en_revision';
    }

    /**
     * Accessor: Obtener la prioridad con formato
     */
    public function getPrioridadFormateadaAttribute()
    {
        $prioridades = [
            'baja' => '🟢 Baja',
            'media' => '🟡 Media',
            'alta' => '🔴 Alta'
        ];

        return $prioridades[$this->prioridad] ?? $this->prioridad;
    }

    /**
     * Accessor: Obtener el tipo con formato
     */
    public function getTipoFormateadoAttribute()
    {
        $tipos = [
            'ronda' => '🏃 Ronda',
            'incidente' => '🚨 Incidente',
            'reporte' => '📋 Reporte'
        ];

        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Accessor: Obtener el origen con formato
     */
    public function getOrigenFormateadoAttribute()
    {
        $origenes = [
            'seguridad' => '🚪 Seguridad',
            'residente' => '🏠 Residente'
        ];

        return $origenes[$this->origen] ?? $this->origen;
    }

    /**
     * Método: Verificar si puede ser editado
     */
    public function puedeSerEditado()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Método: Verificar si es un incidente de residente
     */
    public function esDeResidente()
    {
        return $this->origen === 'residente';
    }

    /**
     * Método: Verificar si es de seguridad
     */
    public function esDeSeguridad()
    {
        return $this->origen === 'seguridad';
    }

    /**
     * Método: Marcar como resuelto
     */
    public function marcarComoResuelto($userId, $observaciones = null)
    {
        $this->update([
            'estado' => 'resuelto',
            'resuelto_por' => $userId,
            'fecha_resolucion' => Carbon::now(),
            'observaciones' => $observaciones ?: $this->observaciones
        ]);
    }
}