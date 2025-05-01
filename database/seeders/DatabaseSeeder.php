<?php

namespace Database\Seeders;
use App\Models\CargoEmpleados;
use App\Models\Cliente;
use App\Models\Clasificadore;
use App\Models\Empleado;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
 
use Database\Seeders\CargoEmpleadosSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RolesSeeder::class,
            UsuariosSeeder::class,
            ClasificadoresSeeder::class,
            CargoEmpleadosSeeder::class, // ✅ nombre correcto
            EmpleadosSeeder::class,
            ResidentesSeeder::class,
        ]);
    }
}
