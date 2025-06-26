<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReclamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reclamos')->insert([
            [
                'tipo' => 'reclamo',
                'titulo' => 'Problema con la calefacción',
                'descripcion' => 'La calefaccion de la caseta no está funcionando correctamente. Necesita reparación urgente.',
                'adjunto' => null,
                'fechaCreacion' => now(),
                'estado' => 'pendiente',
                'respuesta' => null,
                'residente_id' => null,
                'empleado_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo' => 'sugerencia',
                'titulo' => 'Sugerencia para mejorar el jardín',
                'descripcion' => 'Me gustaría sugerir la plantación de más flores y árboles en el jardín para mejorar el ambiente.',
                'adjunto' => null,
                'fechaCreacion' => now(),
                'estado' => 'pendiente',
                'respuesta' => null,
                'residente_id' => 2,
                'empleado_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo' => 'reclamo',
                'titulo' => 'Fuga de agua en el baño',
                'descripcion' => 'Hay una fuga de agua en el baño del segundo piso. Necesita ser reparado inmediatamente.',
                'adjunto' => 'fuga_bano.jpg',
                'fechaCreacion' => now(),
                'estado' => 'abierto',
                'respuesta' => 'El personal de mantenimiento está al tanto del problema y se programará la reparación pronto.',
                'residente_id' => 3,
                'empleado_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo' => 'sugerencia',
                'titulo' => 'Agregar más bancos en el parque',
                'descripcion' => 'Sería excelente si pudieran colocar más bancos en el parque para mayor comodidad de los residentes.',
                'adjunto' => null,
                'fechaCreacion' => now(),
                'estado' => 'resuelto',
                'respuesta' => 'Gracias por la sugerencia. Se está evaluando la posibilidad de agregar más bancos en el parque.',
                'residente_id' => 4,
                'empleado_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
