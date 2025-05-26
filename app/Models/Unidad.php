<?php
// app/Models/Unidad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    // Si tu tabla se llama de forma distinta, descomenta y ajusta:
    protected $table = 'unidades';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'placa',
        'marca',
        'capacidad',
        'estado',
        'personas_por_unidad',
        'tiene_mascotas',
        'vehiculos',
    ];

    // Conversión de tipos
    protected $casts = [
        'capacidad'           => 'integer',
        'personas_por_unidad' => 'integer',
        'tiene_mascotas'      => 'boolean',
        'vehiculos'           => 'integer',
    ];

    /**
     * Una Unidad puede tener muchos Residentes.
     */
    public function residentes()
    {
        return $this->hasMany(Residente::class);
    }

    /**
     * Scope: sólo unidades con estado 'activa'.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }
}
