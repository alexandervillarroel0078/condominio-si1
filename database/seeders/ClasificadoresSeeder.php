<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasificadoresSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            // Roles del sistema
            ['descripcion' => 'SUPER ADMINISTRADOR', 'tipo' => 'ROL_USUARIO', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'ADMINISTRADOR', 'tipo' => 'ROL_USUARIO', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'PORTERO', 'tipo' => 'ROL_USUARIO', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'GUARDIA DE SEGURIDAD', 'tipo' => 'ROL_USUARIO', 'created_at' => now(), 'updated_at' => now()],

            // Tipos de empleado (si se registra personal del condominio)
            ['descripcion' => 'PERSONAL DE LIMPIEZA', 'tipo' => 'TIPO_EMPLEADO', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'GUARDIA', 'tipo' => 'TIPO_EMPLEADO', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'ENCARGADO DE MANTENIMIENTO', 'tipo' => 'TIPO_EMPLEADO', 'created_at' => now(), 'updated_at' => now()],

            // Acciones para bitácora
            ['descripcion' => 'INGRESO', 'tipo' => 'TIPO_ACCION', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'SALIDA', 'tipo' => 'TIPO_ACCION', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'INSERTAR', 'tipo' => 'TIPO_ACCION', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'BORRAR', 'tipo' => 'TIPO_ACCION', 'created_at' => now(), 'updated_at' => now()],
            ['descripcion' => 'MODIFICAR', 'tipo' => 'TIPO_ACCION', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('clasificadores')->insert($tipos);
    }
}
