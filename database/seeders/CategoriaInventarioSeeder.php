<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaInventario;

class CategoriaInventarioSeeder extends Seeder
{
    public function run(): void
    {
        CategoriaInventario::insert([
            ['nombre' => 'Herramientas', 'descripcion' => 'Herramientas manuales y eléctricas'],
            ['nombre' => 'Equipos', 'descripcion' => 'Equipos electrónicos y técnicos'],
            ['nombre' => 'Mobiliario', 'descripcion' => 'Mesas, sillas, estantes y otros muebles'],
            ['nombre' => 'Seguridad', 'descripcion' => 'Extintores, cámaras, alarmas'],
        ]);
    }
}
