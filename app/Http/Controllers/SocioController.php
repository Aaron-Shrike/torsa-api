<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Solicitud;
use App\Models\GaranteSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socio = Socio::all();
        return \response($socio);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
                $request->validate([
                    'dniS'=>'required',
                    'nombreS'=>'required',
                    'apePaternoS'=>'required',
                    'apeMaternoS'=>'required',
                    'fecNacimientoS'=>'required',
                    'telefonoS'=>'required',
                    'domicilioS'=>'required'
                ]);
                $socio = new Socio([
                    'dni' =>$request->get('dniS'),
                    'nombre' =>$request->get('nombreS'),
                    'apePaterno'=>$request->get('apePaternoS'),
                    'apeMaterno' =>$request->get('apeMaternoS'),
                    'fecNacimiento' =>$request->get('fecNacimientoS'),
                    'telefono'=>$request->get('telefonoS'),
                    'domicilio' =>$request->get('domicilioS'),
                    'tipo' =>'Socio',
                    'activo'=>1
                ]);
                $socio->save();
        DB::commit();
        } catch (\Exception $e) {
        DB::rollback();
        return $e->getMessage();
        }
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

    public function buscarSocioHabilitado($dni){

        $data=array();
        $socio = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno","socio.apeMaterno",
               "socio.fecNacimiento","socio.telefono","socio.domicilio","socio.tipo")
             ->where([
                  "socio.dni"=>$dni,
                  "socio.activo"=>"1"
                 ])
             ->first();
            if(isset($socio['dni']))
            {  
                $verifica = Socio::select('solicitud.estado','socio.codSocio')
                    ->join('solicitud','solicitud.codSocio','socio.codSocio')
                    ->where('socio.codSocio','=',$socio['codSocio'])
                    ->orderBy('solicitud.fecha','desc')
                    ->first();
                    if(isset($verifica['codSocio']))
                    {   
                        if($verifica['estado']=='REC' or $verifica['estado']=='ANU')
                        {
                            $verificaGarante = GaranteSolicitud::select('solicitud.estado','garantesolicitud.codSocio')
                            ->join('solicitud','solicitud.codSolicitud','garantesolicitud.codSolicitud')
                            ->join('socio','socio.codSocio','garantesolicitud.codSocio')
                            ->where('garantesolicitud.codSocio','=',$verifica['codSocio'])
                            ->orderBy('solicitud.fecha','desc')
                            ->first();
                            if(isset($verificaGarante['codSocio']))
                            {
                                if($verificaGarante['estado']=='REC' or $verificaGarante['estado']=='ANU')
                                {
                                    $data = [
                                        'codSocio' => $socio['codSocio'],
                                        'dni' => $socio['dni'],
                                        'nombre'=> $socio['nombre'],
                                        'apePaterno'=> $socio['apePaterno'],
                                        'apeMaterno'=> $socio['apeMaterno'],
                                        'fecNacimiento'=> $socio['fecNacimiento'],
                                        'telefono'=> $socio['telefono'],
                                        'domicilio'=> $socio['domicilio'],
                                        'tipo'=> $socio['tipo'],
                                    ];
                                    return response($data);
                                }
                                else
                                {
                                    return "Es garante de una solicitud pendiente";
                                }
                            }
                            else
                            {
                                $data = [
                                    'codSocio' => $socio['codSocio'],
                                    'dni' => $socio['dni'],
                                    'nombre'=> $socio['nombre'],
                                    'apePaterno'=> $socio['apePaterno'],
                                    'apeMaterno'=> $socio['apeMaterno'],
                                    'fecNacimiento'=> $socio['fecNacimiento'],
                                    'telefono'=> $socio['telefono'],
                                    'domicilio'=> $socio['domicilio'],
                                    'tipo'=> $socio['tipo'],
                                ];
                                return response($data);
                            }
                        }
                        else
                        {
                            return "Tiene una solicitud pendiente";
                        }
                    
                    }
                    else
                    {
                        $verificaGarante = GaranteSolicitud::select('solicitud.estado','garantesolicitud.codSocio')
                        ->join('solicitud','solicitud.codSolicitud','garantesolicitud.codSolicitud')
                        ->join('socio','socio.codSocio','garantesolicitud.codSocio')
                        ->where('garantesolicitud.codSocio','=',$socio['codSocio'])
                        ->orderBy('solicitud.fecha','desc')
                        ->first();
                        if(isset($verificaGarante['codSocio']))
                        {
                            if($verificaGarante['estado']=='REC' or $verificaGarante['estado']=='ANU')
                            {
                                $data = [
                                    'codSocio' => $socio['codSocio'],
                                    'dni' => $socio['dni'],
                                    'nombre'=> $socio['nombre'],
                                    'apePaterno'=> $socio['apePaterno'],
                                    'apeMaterno'=> $socio['apeMaterno'],
                                    'fecNacimiento'=> $socio['fecNacimiento'],
                                    'telefono'=> $socio['telefono'],
                                    'domicilio'=> $socio['domicilio'],
                                    'tipo'=> $socio['tipo'],
                                ];
                                return response($data);
                            }
                            else
                            {
                                return "Es garante de una solicitud pendiente";
                            }
                        }
                        else
                        {
                            $data = [
                                'codSocio' => $socio['codSocio'],
                                'dni' => $socio['dni'],
                                'nombre'=> $socio['nombre'],
                                'apePaterno'=> $socio['apePaterno'],
                                'apeMaterno'=> $socio['apeMaterno'],
                                'fecNacimiento'=> $socio['fecNacimiento'],
                                'telefono'=> $socio['telefono'],
                                'domicilio'=> $socio['domicilio'],
                                'tipo'=> $socio['tipo'],
                            ];
                            return response($data);
                        }
                    }
            }
            else
            {
                return "Ingrese nuevo socio";
            }
    }
}
