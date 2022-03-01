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
        // Tipo PROMOTOR
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Promotor";
        $tipoUsuario->save();
        // Tipo RECEPCIONISTA
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Recepcionista";
        $tipoUsuario->save();
        // Tipo Cajero
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Cajero";
        $tipoUsuario->save();
        // Tipo Administrador
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->descripcion = "Administrador";
        $tipoUsuario->save();
    }
}
