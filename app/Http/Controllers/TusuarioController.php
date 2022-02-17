<?php

namespace App\Http\Controllers;

use App\Models\Tcargo;
use App\Models\Tusuario;
use Illuminate\Http\Request;

class TusuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tusuarios = Tusuario::all();
        return \response($tusuarios);
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
            'descripcion' => 'required'
        ]);

        $tusuarios = Tusuario::create($request->all());

        return response()->json($tusuarios,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Tusuario::find($id);
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
        $tusuario = Tusuario::findOrFail($id)->update($request->all());

        return response()->json($tusuario,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tususario = Tusuario::findOnFile($id);
        $tususario->delete();
        return response()->json(null,204);
    }
}
