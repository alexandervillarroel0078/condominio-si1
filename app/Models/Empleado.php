<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'ci', 'telefono', 'direccion', 'cargo_empleado_id'
    ];

    public function cargo()
    {
        return $this->belongsTo(CargoEmpleado::class, 'cargo_empleado_id');
    }
}
