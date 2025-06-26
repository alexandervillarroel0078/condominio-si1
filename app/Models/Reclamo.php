<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $table = 'reclamos';

    protected $fillable = [
        'tipo',
        'titulo',
        'descripcion',
        'adjunto',
        'fechaCreacion',
        'estado',
        'respuesta',
        'residente_id',
        'empleado_id',
    ];

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
