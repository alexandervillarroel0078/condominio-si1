<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Residente;
class ResidentesSeeder extends Seeder
{
    public function run(): void
    {
        Residente::factory()->count(30)->create(); // Puedes cambiar a 50 o más si deseas
    }
}