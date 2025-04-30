<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            // Usuarios
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',

            // Roles
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role',

            // Empleados
            'ver-empleado',
            'crear-empleado',
            'editar-empleado',
            'eliminar-empleado',

            // Residentes
            'ver-residente',
            'crear-residente',
            'editar-residente',
            'eliminar-residente',

            // Bitácora
            'ver-bitacora',

            // Perfil
            'ver-perfil',
            'editar-perfil',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}
