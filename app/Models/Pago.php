<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuota_id',
        'monto_pagado',
        'fecha_pago',
        'metodo',
        'observacion',
        'user_id',
    ];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
