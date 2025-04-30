<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoEmpleado extends Model
{
    protected $fillable = ['cargo', 'estado'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
