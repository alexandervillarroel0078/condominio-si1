<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('12345678');

        // Usuario ADMINISTRADOR
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $admin->assignRole('ADMINISTRADOR');

        // Usuario DIRECTIVA
        $directiva = User::create([
            'name' => 'directiva',
            'email' => 'directiva@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $directiva->assignRole('DIRECTIVA');

        // Usuario RESIDENTE
        $residente = User::create([
            'name' => 'residente',
            'email' => 'residente@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $residente->assignRole('RESIDENTE');

        // Usuario CONTROL ACCESO
        $control = User::create([
            'name' => 'acceso',
            'email' => 'acceso@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $control->assignRole('CONTROL ACCESO');
    }
}
