<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provincia = Provincia::all();
        return \response($provincia);
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
            'codDepartamento' => 'required',
            'nombre' => 'required'
        ]);

        $provincia = Provincia::create($request->all());

        return response()->json($provincia,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Provincia::find($id);
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
        $provincia = Provincia::findOrFail($id)->update($request->all());

        return response()->json($provincia,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provincia = Provincia::findOnFile($id);
        $provincia->delete();
        return response()->json(null,204);
    }
}
