<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CodigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('codigos')->insert([
            'codigo' => 5027,
            'description' => 'rb3 activos > modulo',
        ]);
        DB::table('codigos')->insert([
            'codigo' => 5029,
            'description' => 'rb3 activos < modulo',
        ]);
        DB::table('codigos')->insert([
            'codigo' => 5037,
            'description' => 'frm activos > modulo',
        ]);
        DB::table('codigos')->insert([
            'codigo' => 5039,
            'description' => 'frm activos < modulo',
        ]);
    }
}
