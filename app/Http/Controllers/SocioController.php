<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dni)
    {
        $socio = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno","socio.apeMaterno",
        "socio.fecNacimiento","socio.telefono","socio.domicilio","socio.tipo")
        ->where([
            "socio.dni"=>$dni,
            "socio.activo"=>"1"
            ])
        ->first();

    
        return response()->json($socio,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function buscarSocioHabilitado($dni)
    {
        //dd(request()->all());
        $socio = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno","socio.apeMaterno",
                "socio.fecNacimiento","socio.telefono","socio.domicilio","socio.tipo")
                ->where([
                    "socio.dni"=>$dni,
                    "socio.activo"=>"1"
                    ])
                ->first();

            
        return response()->json($socio,200);
    }
}
