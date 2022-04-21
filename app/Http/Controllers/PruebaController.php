<?php

namespace App\Http\Controllers;

use App\Models\Socio;

class PruebaController extends Controller
{
    //
    public function Prueba()
    {
        $datosPrueba = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno",
        "socio.apeMaterno","socio.fecNacimiento","socio.telefono","socio.domicilio",
        "socio.tipo", "socio.activo", "socio.codDistrito","distrito.codProvincia",
        "provincia.codDepartamento")
        ->join('distrito','distrito.codDistrito','socio.codDistrito')
        ->join('provincia','provincia.codProvincia','distrito.codProvincia')
        ->join('departamento','departamento.codDepartamento','provincia.codDepartamento')
        ->where([
            "socio.dni"=>'74125639',
        ])
        ->first();

        return response([$datosPrueba]);
    }
}
