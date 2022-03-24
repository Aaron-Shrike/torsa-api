<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use Illuminate\Http\Request;

class DistritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distrito = Distrito::all();
        return \response($distrito);
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
            'codProvincia' => 'required',
            'nombre' => 'required'
        ]);

        $distrito = Distrito::create($request->all());

        return response()->json($distrito,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Distrito::find($id);
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
        $distrito = Distrito::findOrFail($id)->update($request->all());

        return response()->json($distrito,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $distrito = Distrito::findOnFile($id);
        $distrito->delete();
        return response()->json(null,204);
    }
    public function ObtenerDistritos($codProvincia)
    {
        try
        {
            $consulta=Distrito::select('codDistrito','nombre')
                        ->where('codProvincia',$codProvincia)
                        ->get();
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }
}
