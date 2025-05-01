<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacoras';

    protected $fillable = [
        'user_id',
        'usuario',
        'accion',
        'fecha_hora',
        'id_operacion',
        'ip',
        'descripcion',
    ];

    public $timestamps = true; // Esto es por defecto y manejará los campos created_at y updated_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
