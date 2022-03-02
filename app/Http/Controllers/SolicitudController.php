<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GaranteSolicitud;
use App\Models\Socio;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\SocioController;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //traer datos de base datos de solic reg
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
        //registra la solici
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
        //mostrando una solicitud segun su codigo
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
    public function registrarSolicitud(Request $request)
    {    
        DB::beginTransaction();
        try {
                //Solicitud y socio
                if($request['datosSolicitud']['codSocio']!=null)
                {
                    $solicitud = new Solicitud([
                        'codUsuario'=>$request['datosSolicitud']['codUsuario'],
                        'codSocio'=>$request['datosSolicitud']['codSocio'],
                        'monto'=>$request['datosSolicitud']['monto'],
                        'motivo'=>$request['datosSolicitud']['motivo'],
                        'fecha'=>Carbon::today()->format('Y-m-d'),
                        'estado'=>'PVC'
                    ]); 

                    $socio = Socio::find($request->codSocio);
                    if($socio->tipo=='Garante')
                    {
                        $socio->tipo = 'Socio';
                        $socio->save();
                    }
                    
                    $solicitud->save();
                }
                else
                {
                    $socio = new Socio([
                        'dni' =>$request['datosSocio']['dni'],
                        'nombre' =>$request['datosSocio']['nombre'],
                        'apePaterno'=>$request['datosSocio']['apePaterno'],
                        'apeMaterno' =>$request['datosSocio']['apeMaterno'],
                        'fecNacimiento' =>$request['datosSocio']['fecNacimiento'],
                        'telefono'=>$request['datosSocio']['telefono'],
                        'domicilio' =>$request['datosSocio']['domicilio'],
                        'tipo' =>'Socio',
                        'activo'=>1
                    ]);
                    $socio->save();
                    $solicitud = new Solicitud([
                        'codSocio'=>$socio->codSocio,
                        'codUsuario'=>$request['datosSolicitud']['codUsuario'],
                        'monto'=>$request['datosSolicitud']['monto'],
                        'motivo'=>$request['datosSolicitud']['motivo'],
                        'fecha'=>Carbon::today()->format('Y-m-d'),
                        'estado'=>'PVC'
                    ]); 
                    $solicitud->save();
                }
                //Garante uno
                if($request['datosGarante1']['codGarante1']!=null)
                {
                   $garanteSolicitudUno = new GaranteSolicitud([
                        'codSolicitud'=>$solicitud->codSolicitud,
                        'codSocio'=>$request['datosGarante1']['codGarante1']
                    ]);
                   $garanteSolicitudUno->save();
                }
                else
                {
                    $garanteUno = new Socio([
                        'dni'=>$request['datosGarante1']['dni'],
                        'nombre' =>$request['datosGarante1']['nombre'],
                        'apePaterno'=>$request['datosGarante1']['apePaterno'],
                        'apeMaterno' =>$request['datosGarante1']['apeMaterno'],
                        'fecNacimiento' =>$request['datosGarante1']['fecNacimiento'],
                        'telefono'=>$request['datosGarante1']['telefono'],
                        'domicilio' =>$request['datosGarante1']['domicilio'],
                        'tipo'=>'Garante',
                        'activo'=>1
                        ]);
                    $garanteUno->save();
                                    
                    $garanteSolicitudUno = new GaranteSolicitud([
                        'codSolicitud'=>$solicitud->codSolicitud,
                        'codSocio'=>$garanteUno->codSocio
                    ]);
                    $garanteSolicitudUno->save();
                }
                //Garante dos
                if($request['datosGarante2']['codGarante2']!=null)
                {
                     $garanteSolicitudDos = new GaranteSolicitud([
                         'codSolicitud'=>$solicitud->codSolicitud,
                         'codSocio'=>$request['datosGarante2']['codGarante2']
                     ]);
                    $garanteSolicitudDos->save();
                }
                else
                {
                    $garanteDos = new Socio([
                        'dni'=>$request['datosGarante2']['dni'],
                        'nombre' =>$request['datosGarante2']['nombre'],
                        'apePaterno'=>$request['datosGarante2']['apePaterno'],
                        'apeMaterno' =>$request['datosGarante2']['apeMaterno'],
                        'fecNacimiento' =>$request['datosGarante2']['fecNacimiento'],
                        'telefono'=>$request['datosGarante2']['telefono'],
                        'domicilio' =>$request['datosGarante2']['domicilio'],
                        'tipo'=>'Garante',
                        'activo'=>1
                           ]);
                    $garanteDos->save();
                    $garanteSolicitudDos = new GaranteSolicitud([
                           'codSolicitud'=>$solicitud->codSolicitud,
                           'codSocio'=>$garanteDos->codSocio
                    ]);
                    $garanteSolicitudDos->save();
                }
                          
            DB::commit();
        }catch (\Exception $e) {
        DB::rollback();
        return $e->getMessage();
        }
    }
    public function listarSolicitudesDia(){
        //dd(request()->all());

        $fechaDeHoy = Carbon::today()->format('Y-m-d');

        //$codigo = Auth::codUsuario();
        $codigo = auth()->user()->codUsuario;
        
        $solicitudesDia = Solicitud::select('solicitud.codSolicitud','solicitud.codUsuario',
                        'solicitud.codSocio','solicitud.monto','solicitud.motivo','solicitud.fecha','solicitud.estado',
                        'socio.codSocio','socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno','socio.telefono')
                        ->join("usuario","usuario.codUsuario","solicitud.codUsuario")
                        ->join("socio","socio.codSocio","solicitud.codSocio")
                        ->where([
                            'solicitud.codUsuario'=>$codigo,
                            'solicitud.fecha'=>$fechaDeHoy,
                            'solicitud.estado'=>"1"
                            ])
                            ->get();

        return response()->json($solicitudesDia,200);
    }

   
}
