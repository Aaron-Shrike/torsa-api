<?php

namespace Database\Seeders;

use App\Models\Trabajador;
use Illuminate\Database\Seeder;

class TrabajadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trabajador = new Trabajador();
        $trabajador->nombre = "Juan";
        $trabajador->apePaterno = "ApePJuan";
        $trabajador->apeMaterno = 'ApeMJuan';
        $trabajador->fecNacimiento = "1999-09-19";
        $trabajador->telefono = "123456781";
        $trabajador->domicilio = "asdasd domicilio juan";
        $trabajador->correo = "juan@my.com";
        $trabajador->codTipoCargo = "1";
        $trabajador->codConEmergencia = "1";
        $trabajador->save();
        
        $trabajador = new Trabajador();
        $trabajador->nombre = "Manuel";
        $trabajador->apePaterno = "ApePManuel";
        $trabajador->apeMaterno = 'ApeMManuel';
        $trabajador->fecNacimiento = "1999-09-09";
        $trabajador->telefono = "123456782";
        $trabajador->domicilio = "asdasd domicilio manuel";
        $trabajador->correo = "manuel@my.com";
        $trabajador->codTipoCargo = "1";
        $trabajador->codConEmergencia = "1";
        $trabajador->save();
    }
}
