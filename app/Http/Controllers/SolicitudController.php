<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GaranteSolicitud;
use App\Models\Socio;
use App\Models\Solicitud;
use Carbon\Carbon;

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
        
        $solicitudesDia = Solicitud::select('solicitud.codSolicitud','solicitud.codUsuario',
                        'solicitud.codSocio','solicitud.monto','solicitud.motivo','solicitud.fecha','solicitud.estado')
                        ->join("usuario","usuario.codUsuario","solicitud.codUsuario")
                        ->join("socio","socio.codSocio","solicitud.codSocio")
                        ->where([
                            'solicitud.fecha'=>$fechaDeHoy,
                            'solicitud.estado'=>"1"
                            ])
                            ->get();

        return response()->json($solicitudesDia,200);
    }

    public function registrarSolicitud(Request $request){
        //dd(request()->all());
        $request->validate([
            'codUsuario'=>'required',
            'monto'=>'required',
            'motivo'=>'required',
            'fechaS'=>'required',
            'dni'=>'required',
            'nombreS'=>'required',
            'apePaternoS'=>'required',
            'apeMaternoS'=>'required',
            'fecNacimientoS'=>'required',
            'telefonoS'=>'required',
            'domicilioS'=>'required',
            'dniG1'=>'required',
            'nombreG1'=>'required',
            'apePaternoG1'=>'required',
            'apeMaternoG1'=>'required',
            'fecNacimientoG1'=>'required',
            'telefonoG1'=>'required',
            'domicilioG1'=>'required',
            'dniG2'=>'required',
            'nombreG2'=>'required',
            'apePaternoG2'=>'required',
            'apeMaternoG2'=>'required',
            'fecNacimientoG2'=>'required',
            'telefonoG2'=>'required',
            'domicilioG2'=>'required'
        ]);
        $socio = new Socio([
            'dni'=>$request->get('dniS'),
            'nombre'=>$request->get('nombreS'),
            'apePaterno'=>$request->get('apePaternoS'),
            'apeMaterno'=>$request->get('apeMaternoS'),
            'fecNacimiento'=>$request->get('fecNacimientoS'),
            'telefono'=>$request->get('telefonoS'),
            'domicilio'=>$request->get('domicilioS'),
            'tipo'=>$request->get('tipoS'),//no se si así se asigna
            'activo'=>$request->get('activoS')
        ]);
        $socio->save();
        
        $solicitud = new Solicitud([
            'codUsuario'=>$request->get('codUsuario'),
            'codSocio'=>$socio->codSocio,
            'monto'=>$request->get('monto'),
            'motivo'=>$request->get('motivo'),
            //'fecha'=>Carbon::now(),//no lo he probado
            'fecha'=>$request->get('fechaS'),
            'estado'=>'PVC'
        ]);
        $solicitud->save();

        $garanteUno = new Socio([
            'dni'=>$request->get('dniG1'),
            'nombre'=>$request->get('nombreG1'),
            'apePaterno'=>$request->get('apePaternoG1'),
            'apeMaterno'=>$request->get('apeMaternoG1'),
            'fecNacimiento'=>$request->get('fecNacimientoG1'),
            'telefono'=>$request->get('telefonoG1'),
            'domicilio'=>$request->get('domicilioG1'),
            'tipo'=>$request->get('tipog1'),
            'activo'=>$request->get('activog1')
        ]);
        $garanteUno->save();

        $garanteSolicitudUno = new GaranteSolicitud([

            'codSolicitud'=>$solicitud->codSolicitud,
            'codSocio'=>$garanteUno->codSocio
        ]);
        $garanteSolicitudUno->save();

        $garanteDos = new Socio([
            'dni'=>$request->get('dniG2'),
            'nombre'=>$request->get('nombreG2'),
            'apePaterno'=>$request->get('apePaternoG2'),
            'apeMaterno'=>$request->get('apeMaternoG2'),
            'fecNacimiento'=>$request->get('fecNacimientoG2'),
            'telefono'=>$request->get('telefonoG2'),
            'domicilio'=>$request->get('domicilioG2'),
            'tipo'=>$request->get('tipog2'),
            'activo'=>$request->get('activog2')
        ]);
        $garanteDos->save();
        
        $garanteSolicitudDos = new GaranteSolicitud([

            'codSolicitud'=>$solicitud->codSolicitud,
            'codSocio'=>$garanteDos->codSocio
        ]);
        $garanteSolicitudDos->save();
    }
}
