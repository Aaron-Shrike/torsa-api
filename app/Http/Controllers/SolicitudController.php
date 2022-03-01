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

    public function registrarSolicitud(Request $request)
    {    
        DB::beginTransaction();
        try {
                if($request->codSocio!=null)
                {
                    $solicitud = new Solicitud([
                        'codUsuario'=>$request->get('codUsuario'),
                        'codSocio'=>$request->get('codSocio'),
                        'monto'=>$request->get('montoS'),
                        'motivo'=>$request->get('motivoS'),
                        'fecha'=>Carbon::today()->format('Y-m-d'),
                        'estado'=>'PVC'
                    ]); 

                    //cambia a socio si comenzo como garante
                        $socio = Socio::find($request->codSocio);
                        $socio->tipo = 'Socio';
                        $socio->save();
                        $solicitud->save();
                }
                else
                {
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
                    $solicitud = new Solicitud([
                        'codSocio'=>$socio->codSocio,
                        'codUsuario'=>$request->get('codUsuario'),
                        'monto'=>$request->get('montoS'),
                        'motivo'=>$request->get('motivoS'),
                        'fecha'=>Carbon::today()->format('Y-m-d'),
                        'estado'=>'PVC'
                    ]); 
                    $solicitud->save();
                }
                //uno
                if($request->codGarante1!=null)
                {
                   $garanteSolicitudUno = new GaranteSolicitud([
                        'codSolicitud'=>$solicitud->codSolicitud,
                        'codSocio'=>$request->get('codGarante1')
                    ]);
                   $garanteSolicitudUno->save();
                }
                else
                {
                    $garanteUno = new Socio([
                       'dni'=>$request->get('dniG1'),
                       'nombre'=>$request->get('nombreG1'),
                       'apePaterno'=>$request->get('apePaternoG1'),
                       'apeMaterno'=>$request->get('apeMaternoG1'),
                       'fecNacimiento'=>$request->get('fecNacimientoG1'),
                        'telefono'=>$request->get('telefonoG1'),
                        'domicilio'=>$request->get('domicilioG1'),
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
                //dos
                if($request->codGarante2!=null)
                {
                     $garanteSolicitudDos = new GaranteSolicitud([
                         'codSolicitud'=>$solicitud->codSolicitud,
                         'codSocio'=>$request->get('codGarante2')
                     ]);
                    $garanteSolicitudDos->save();
                }
                else
                {
                    $garanteDos = new Socio([
                            'dni'=>$request->get('dniG2'),
                            'nombre'=>$request->get('nombreG2'),
                            'apePaterno'=>$request->get('apePaternoG2'),
                            'apeMaterno'=>$request->get('apeMaternoG2'),
                            'fecNacimiento'=>$request->get('fecNacimientoG2'),
                            'telefono'=>$request->get('telefonoG2'),
                            'domicilio'=>$request->get('domicilioG2'),
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
}
