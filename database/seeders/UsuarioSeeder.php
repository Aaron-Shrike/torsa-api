<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = new Usuario();
        $usuario->dni = "12345671";
        $usuario->contrasenia = "1234";
        $usuario->activo = '1';
        $usuario->codTrabajador = "1";
        $usuario->codTipoUsuario = "1";
        $usuario->save();

        $usuario = new Usuario();
        $usuario->dni = "12345672";
        $usuario->contrasenia = "1234";
        $usuario->activo = '1';
        $usuario->codTrabajador = "1";
        $usuario->codTipoUsuario = "4";
        $usuario->save();
    }
}
