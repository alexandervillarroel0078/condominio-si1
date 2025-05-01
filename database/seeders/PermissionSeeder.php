<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Módulo: Usuarios
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // Módulo: Roles y permisos
        Permission::create(['name' => 'ver roles']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'eliminar roles']);

        // Módulo: Empleados
        Permission::create(['name' => 'ver empleados']);
        Permission::create(['name' => 'crear empleados']);
        Permission::create(['name' => 'editar empleados']);
        Permission::create(['name' => 'eliminar empleados']);

        // Módulo: Residentes
        Permission::create(['name' => 'ver residentes']);
        Permission::create(['name' => 'crear residentes']);
        Permission::create(['name' => 'editar residentes']);
        Permission::create(['name' => 'eliminar residentes']);

        // Módulo: Bitácora
        Permission::create(['name' => 'ver bitácora']);
    }
}
