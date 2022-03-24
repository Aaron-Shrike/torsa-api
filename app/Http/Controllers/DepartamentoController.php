<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departamento = Departamento::all();
        return \response($departamento);
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
            'nombre' => 'required'
        ]);

        $departamento = Departamento::create($request->all());

        return response()->json($departamento,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Departamento::find($id);
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
        $departamento = Departamento::findOrFail($id)->update($request->all());

        return response()->json($departamento,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departamento = Departamento::findOnFile($id);
        $departamento->delete();
        return response()->json(null,204);
    }

    public function ObtenerDepartamento()
    {
        $data=array();
        try
        {
            $consulta = Departamento::select('departamento.codDepartamento','departamento.nombre')
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
