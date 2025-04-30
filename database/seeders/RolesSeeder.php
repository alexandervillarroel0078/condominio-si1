<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        
        $roles = [
            'ADMINISTRADOR',
            'DIRECTIVA',
            'RESIDENTE',
            'CONTROL ACCESO', // Para entrada y salida
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $admin     = Role::where('name', 'ADMINISTRADOR')->first();
        $directiva = Role::where('name', 'DIRECTIVA')->first();
        $residente = Role::where('name', 'RESIDENTE')->first();
        $control   = Role::where('name', 'CONTROL ACCESO')->first();

     
         $permisos = Permission::pluck('name')->toArray();
         $admin->syncPermissions($permisos);
         

         // Directiva con permisos limitados
        $directiva->syncPermissions([
            'ver-user', 'ver-residente', 'ver-bitacora', 'ver-perfil'
        ]);

        // Residente solo puede ver y editar su perfil
        $residente->syncPermissions([
            'ver-perfil', 'editar-perfil'
        ]);

        // Control de acceso solo puede ver residentes y bitácora
        $control->syncPermissions([
            'ver-residente', 'ver-bitacora'
        ]);
    }
}
