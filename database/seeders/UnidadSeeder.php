<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unidad;
class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unidad::insert([
            [
                'codigo' => 'U-001',
                'placa' => 'ABC123',
                'marca' => 'Toyota',
                'capacidad' => 4,
                'estado' => 'activa',
                'personas_por_unidad' => 2,
                'tiene_mascotas' => true,
                'vehiculos' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'U-002',
                'placa' => 'XYZ789',
                'marca' => 'Nissan',
                'capacidad' => 6,
                'estado' => 'activa',
                'personas_por_unidad' => 3,
                'tiene_mascotas' => false,
                'vehiculos' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agrega más si lo deseas
        ]);
    }
    
}
