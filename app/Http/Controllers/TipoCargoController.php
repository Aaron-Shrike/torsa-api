<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tcargo;


class TcargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tcargos = Tcargo::all();
        return //\response($tcargos);
        response()->json($tcargos, 200);
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

        $tcargos = Tcargo::create($request->all());

        return response()->json($tcargos,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Tcargo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //dd(request->all());
    public function update(Request $request, $id)
    {

        $tcargo = Tcargo::findOrFail($id)->update($request->all());

        return response()->json($tcargo,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tcargo = Tcargo::findOrFail($id);
        $tcargo->delete();
        return response()->json(null,204);
    }
}
