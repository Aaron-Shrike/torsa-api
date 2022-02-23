<?php

namespace App\Http\Controllers;

use App\Models\ContactoEmergencia;
use Illuminate\Http\Request;

class ContactoEmergenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cemergencias = ContactoEmergencia::all();
        return \response($cemergencias);
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
            'nombre' => 'required',
            'numero'=>'required',
            'parentesco'=>'required'
        ]);

        $cemergencias = ContactoEmergencia::create($request->all());

        return response()->json($cemergencias,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ContactoEmergencia::findOrFail($id);
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
        $cemergencias = ContactoEmergencia::findOrFail($id)->update($request->all());

        return response()->json($cemergencias,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cemergencia = ContactoEmergencia::findOnFail($id);
        $cemergencia->delete();
        return response()->json(null,204);
    }
}
