<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCargo;
use App\Models\ContactoEmergencia;

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

        TipoCargo::factory(4)->create();
        ContactoEmergencia::factory(4)->create();

        $this->call(TipoUsuarioSeeder::class);
        $this->call(TrabajadorSeeder::class);
        $this->call(UsuarioSeeder::class);
    }
}
