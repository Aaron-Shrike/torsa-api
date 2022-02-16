<?php

namespace App\Http\Controllers;

use App\Models\Cemergencia;
use App\Models\Tcargo;
use App\Models\Trabajador;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trabajador = Trabajador::join("tcargos","tcargos.codTipoCargo","=","trabajadors.codTipoCargo")
        ->join("cemergencias","cemergencias.codConEmergencia","=","trabajadors.codConEmergencia")
        ->select("trabajadors.nombre","trabajadors.apePaterno","trabajadors.apeMaterno","trabajadors.fecNacimiento","trabajadors.telefono",
        "trabajadors.domicilio","trabajadors.correo","tcargos.descripcion",
        "cemergencias.nombre","cemergencias.numero","cemergencias.parentesco")
        ->get();

        return response()->json($trabajador, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreC'=>'required',
            'numero'=>'required',
            'parentesco'=>'required',
            'nombreT'=>'required',
            'apePaterno'=>'required',
            'apeMaterno'=>'required',
            'fecNacimiento'=>'required',
            'telefono'=>'required',
            'domicilio'=>'required',
            'correo'=>'required',
            'codTipoCargo'=>'required',
            'codConEmergencia'=>'required'
        ]);

        $cemergencias = new Cemergencia([
            'nombre'=>$request->get('nombreC'),
            'numero'=>$request->get('numero'),
            'parentesco'=>$request->get('parentesco')
        ]);

         $cemergencias->save();

         $tcargo = new Tcargo([
            'descipcion'=>$request->get("descripcion"),

         ]);

         $tcargo->save();

        $trabajador = new Trabajador([
            'nombre' => $request->get("nombreT"),
            'apePaterno' => $request->get("apePaterno"),
            'apeMaterno' => $request->get("apeMaterno"),
            'fecNacimiento' => $request->get("fecNacimiento"),
            'telefono' => $request->get("telefono"),
            'domicilio' => $request->get("domicilio"),
            'correo' => $request->get("correo"),
            'codTipoCargo' => $request,
            'codConEmergencia' => $request
        ]);
        

        $trabajador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Trabajador::find($id);
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
        $trabajador = Trabajador::findOrFail($id)->update($request->all());

        return response()->json($trabajador,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->delete();
        return response()->json(null,204);
    }

    public function RegistroTrabajador(Request $request)
    {
        $request->validate([
            'nombreC'=>'required',
            'numero'=>'required',
            'parentesco'=>'required',
            'nombreT'=>'required',
            'apePaterno'=>'required',
            'apeMaterno'=>'required',
            'fecNacimiento'=>'required',
            'telefono'=>'required',
            'domicilio'=>'required',
            'correo'=>'required',
            'codTipoCargo'=>'required',
            'codConEmergencia'=>'required'
        ]);

        $cemergencias = new Cemergencia([
            'nombre'=>$request->get('nombreC'),
            'numero'=>$request->get('numero'),
            'parentesco'=>$request->get('parentesco')
        ]);

         $cemergencias->save();

         $tcargo = new Tcargo([
            'descipcion'=>$request->get("descripcion"),

         ]);

         $tcargo->save();

        $trabajador = new Trabajador([
            'nombre' => $request->get("nombreT"),
            'apePaterno' => $request->get("apePaterno"),
            'apeMaterno' => $request->get("apeMaterno"),
            'fecNacimiento' => $request->get("fecNacimiento"),
            'telefono' => $request->get("telefono"),
            'domicilio' => $request->get("domicilio"),
            'correo' => $request->get("correo"),
            'codTipoCargo' => $request,
            'codConEmergencia' => $request
        ]);
        

        $trabajador->save();
    }  
}
