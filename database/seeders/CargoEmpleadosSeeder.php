<?php

namespace Database\Seeders;

use App\Models\CargoEmpleado;
use Illuminate\Database\Seeder;

class CargoEmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            'Miembro de Directiva',
            'Personal de Seguridad',
            'Portero',
            'Encargado de Limpieza',
            'Asistente Administrativo'
        ];
        
        foreach ($cargos as $cargo) {
            CargoEmpleado::create(['cargo' => $cargo]);
        }
    }
}
