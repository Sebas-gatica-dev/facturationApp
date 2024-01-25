<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class ServicioCodigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        
        DB::table('servicio_codigos')->insert([
            'id_servicio' => 1,
            'codigo' => 5027,
        ]);
        DB::table('servicio_codigos')->insert([
            'id_servicio' => 1,
            'codigo' => 5029,
        ]);
        DB::table('servicio_codigos')->insert([
            'id_servicio' => 2,
            'codigo' => 5037,
        ]);
        DB::table('servicio_codigos')->insert([
            'id_servicio' => 2,
            'codigo' => 5039,
        ]);


    }
}
