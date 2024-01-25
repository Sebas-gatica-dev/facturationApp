<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CodigoSeeder;
use Database\Seeders\ServicioSeeder;
use Database\Seeders\ServicioCodigoSeeder;
use Database\Seeders\UserSeeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
           UserSeeder::class,
            CodigoSeeder::class,
            ServicioSeeder::class,
            ServicioCodigoSeeder::class,
        ]);
    }
}
