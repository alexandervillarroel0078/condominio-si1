<?php
// database/seeders/UnidadSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidad;

class UnidadSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de Unidades.
     */
    public function run(): void
    {
        // Crea 20 unidades de prueba
        Unidad::factory()->count(20)->create();
    }
}
