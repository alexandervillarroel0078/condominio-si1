<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        Inventario::insert([
            [
                'nombre' => 'Taladro Bosch',
                'descripcion' => 'Taladro eléctrico industrial',
                'estado' => 'disponible',
                'fecha_adquisicion' => now()->subYears(1),
                'tipo_adquisicion' => 'compra',
                'valor_estimado' => 350.00,
                'vida_util' => 5,
                'valor_residual' => 50.00,
                'ubicacion' => 'Depósito A',
                'categoria_id' => 1,
                'user_id' => 1, // debe existir un usuario
                'area_comun_id' => 1 // debe existir un área común
            ],
            [
                'nombre' => 'Extintor de CO2',
                'descripcion' => 'Extintor ubicado en pasillo B',
                'estado' => 'disponible',
                'fecha_adquisicion' => now()->subMonths(10),
                'tipo_adquisicion' => 'donación',
                'valor_estimado' => 200.00,
                'vida_util' => 3,
                'valor_residual' => 20.00,
                'ubicacion' => 'Pasillo B',
                'categoria_id' => 4,
                'user_id' => 1,
                'area_comun_id' => 1
            ]
        ]);
    }
}