<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    // app/Models/Cuota.php
    protected $fillable = [
        'titulo', // ← ESTA LÍNEA ES CLAVE
        'descripcion',
        'fecha_emision',
        'fecha_vencimiento',
        'monto',
        'estado',
        'residente_id',
        'tipo_cuota_id',
        'user_id',
        'observacion',
    ];

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    public function tipoCuota()
    {
        return $this->belongsTo(TipoCuota::class);
    }
    // Devuelve true si la cuota tiene al menos un pago
    public function estaPagada()
    {
        return $this->pagos()->exists();
    }

    // Devuelve el último pago registrado
    public function ultimoPago()
    {
        return $this->pagos()->latest('fecha_pago')->first();
    }
}
