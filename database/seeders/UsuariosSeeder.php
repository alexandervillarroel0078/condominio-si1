<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $admin->assignRole('Administrador');

        // Usuario DIRECTIVA
        $directiva = User::create([
            'name' => 'directiva',
            'email' => 'directiva@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $directiva->assignRole('Miembro de Directiva');

        // Usuario RESIDENTE
        $residente = User::create([
            'name' => 'residente',
            'email' => 'residente@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $residente->assignRole('Residente');

        // Usuario PORTERO
        $control = User::create([
            'name' => 'portero',
            'email' => 'portero@gmail.com',
            'activo' => 1,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        $control->assignRole('Portero');
    }
}
