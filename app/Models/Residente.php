<?php
// app/Models/Residente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residente extends Model
{
    use HasFactory;

    // Si tu tabla se llama diferente de 'residentes', ajusta:
    // protected $table = 'residentes';

    // Campos asignables en masa
    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'email',
        'tipo_residente',
        'unidad_id',
    ];

    // Conversión de tipos
    protected $casts = [
        'unidad_id' => 'integer',
    ];

    /**
     * Accessor: nombre completo.
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Un Residente pertenece a una Unidad.
     */
    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }
}
