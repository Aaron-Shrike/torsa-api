<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tusuario;
use App\Models\Tcargo;
use App\Models\Cemergencia;

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

       
        Tusuario::factory(3)->create();
        Tcargo::factory(4)->create();
        Cemergencia::factory(3)->create();
    }
}
