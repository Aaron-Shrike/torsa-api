<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuario PROMOTOR Activo
        $usuario = new Usuario();
        $usuario->dni = "12345671";
        $usuario->contrasenia = Hash::make("1234");
        $usuario->codTrabajador = "1";
        $usuario->codTipoUsuario = "1";
        $usuario->activo = '1';
        $usuario->save();
        // Usuario ADMINISTRADOR Activo
        $usuario = new Usuario();
        $usuario->dni = "12345672";
        $usuario->contrasenia = Hash::make("1234");
        $usuario->codTrabajador = "2";
        $usuario->codTipoUsuario = "4";
        $usuario->activo = '1';
        $usuario->save();
        // Usuario PROMOTOR No Activo 
        $usuario = new Usuario();
        $usuario->dni = "12345673";
        $usuario->contrasenia = Hash::make("1234");
        $usuario->codTrabajador = "3";
        $usuario->codTipoUsuario = "1";
        $usuario->activo = '0';
        $usuario->save();
    }
}
