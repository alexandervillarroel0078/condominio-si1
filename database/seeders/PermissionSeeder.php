<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // Roles
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',

            // Empleados
            'ver empleados',
            'crear empleados',
            'editar empleados',
            'eliminar empleados',

            // Cargos
            'ver cargos',
            'crear cargos',
            'editar cargos',
            'eliminar cargos',

            // Residentes
            'ver residentes',
            'crear residentes',
            'editar residentes',
            'eliminar residentes',

            // Unidades
            'ver unidades',
            'crear unidades',
            'editar unidades',
            'eliminar unidades',

            // Empresas
            'ver empresas',
            'crear empresas',
            'editar empresas',
            'eliminar empresas',

            // Mantenimiento
            'ver mantenimiento',

            // Cuotas y Pagos
            'ver cuotas',
            'ver tipos de cuotas',
            'ver pagos',

            // Gastos
            'ver gastos',
            'ver tipos de gastos',

            // Comunicación
            'ver calificaciones',
            'ver comunicados',
            'ver reclamos',
            'ver notificaciones',

            // Seguridad y Accesos
            'ver control de acceso',
            'ver visitas',
            'ver invitaciones',
            'ver vigilancia',

            // Comunidad
            'ver agenda',
            'ver reportes',
            'ver documentos',
            'ver asambleas',
            'ver foro',

            // Bitácora
            'ver bitacora',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}
