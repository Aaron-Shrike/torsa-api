<?php

namespace Database\Seeders;

use App\Models\TipoUsuario;
use Illuminate\Database\Seeder;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Promotor";
        $tipoUsuario->save();

        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Recepcionista";
        $tipoUsuario->save();

        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Cajero";
        $tipoUsuario->save();

        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Administrador";
        $tipoUsuario->save();
    }
}
