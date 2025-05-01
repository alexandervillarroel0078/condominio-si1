<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'Administrador']);
        $admin->givePermissionTo(Permission::all());

       $residente = Role::create(['name' => 'Residente']);
        $residente->givePermissionTo([
             
        ]);

        $portero = Role::create(['name' => 'Portero']);
        $portero->givePermissionTo([
             
        ]);

        $directiva = Role::create(['name' => 'Miembro de Directiva']);
        $directiva->givePermissionTo([
            'ver usuarios',
            'ver roles',
            'ver empleados',
            'ver residentes',
            'ver bitácora',
            
        ]);
    }
}
