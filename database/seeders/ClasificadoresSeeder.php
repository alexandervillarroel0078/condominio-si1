<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasificadoresSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
                ];

        DB::table('clasificadores')->insert($tipos);
    }
}
