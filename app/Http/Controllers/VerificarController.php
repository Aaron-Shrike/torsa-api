<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verificar;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;

class VerificarController extends Controller
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
    public function show($id)
    {
        //
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
    public function VerificacionesSolicitud(Request $request)
    {
        DB::beginTransaction();

        try 
        {
            $solicitud= Solicitud::find($request->get('codSolicitud'));                 
            $verificar= Verificar::select('codVerificar','codSolicitud','v1','v2','v3','v4','estado')
                    ->where('codSolicitud',$request->get('codSolicitud'))
                    ->where('estado', $solicitud->estado)
                    ->first();

            if(isset($verificar['codSolicitud']))
            { 
                if($verificar['estado']==$solicitud->estado && $verificar['codSolicitud']==$solicitud->codSolicitud)
                {
                    $verificar->v1 = $request->get('v1');
                    $verificar->v2 = $request->get('v2');
                    $verificar->v3 = $request->get('v3');
                    
                    $verificar->save();
                }
                else
                {
                    $verificar = new Verificar();
                    $verificar->codSolicitud = $request->get('codSolicitud');
                    
                    $verificar->v1 = $request->get('v1');
                    $verificar->v2 = $request->get('v2');
                    $verificar->v3 = $request->get('v3');
                    $verificar->estado =$solicitud->estado;

                    $verificar->save();
                }
            }
            else
            {
                $verificar = new Verificar();
                $verificar->codSolicitud = $request->get('codSolicitud');
                
                $verificar->v1 = $request->get('v1');
                $verificar->v2 = $request->get('v2');
                $verificar->v3 = $request->get('v3');
                $verificar->estado =$solicitud->estado;
                
                $verificar->save();
            }
            DB::commit();
        } 
        catch (\Exception $e) 
        {
            DB::rollback();

            return $e->getMessage();
        }         
    }

}
