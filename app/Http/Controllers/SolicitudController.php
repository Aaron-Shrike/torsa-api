<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GaranteSolicitud;
use App\Models\Socio;
use App\Models\Solicitud;
use App\Models\Verificar;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\SocioController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Arr;
use Mockery\Undefined;

use function PHPUnit\Framework\arrayHasKey;

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
        $solicitud = Solicitud::all();
        return \response($solicitud);
        //response()->json($solicitud, 200);

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
        return Solicitud::find($id);
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
    public function RegistrarSolicitud(Request $request)
    {    
        DB::beginTransaction();
        try 
        {
            //Solicitud y socio
            if($request['solicitud']['codSocio']!=null)
            {
                $solicitud = new Solicitud([
                    'codUsuario'=>$request['solicitud']['codUsuario'],
                    'codSocio'=>$request['solicitud']['codSocio'],
                    'monto'=>$request['solicitud']['monto'],
                    'motivo'=>$request['solicitud']['motivo'],
                    'fecha'=>Carbon::now(),
                    'estado'=>'PVC'
                ]); 

                $socio = Socio::find($request['solicitud']['codSocio']);
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
                    'codDistrito' =>$request['socio']['codDistrito'],
                    'dni' =>$request['socio']['dni'],
                    'nombre' =>$request['socio']['nombre'],
                    'apePaterno'=>$request['socio']['apePaterno'],
                    'apeMaterno' =>$request['socio']['apeMaterno'],
                    'fecNacimiento' =>$request['socio']['fecNacimiento'],
                    'telefono'=>$request['socio']['telefono'],
                    'domicilio' =>$request['socio']['domicilio'],
                    'codDistrito'=>$request['socio']['codDistrito'],
                    'tipo' =>'Socio',
                    'activo'=>1
                ]);
                $socio->save();
                $solicitud = new Solicitud([
                    'codSocio'=>$socio->codSocio,
                    'codUsuario'=>$request['solicitud']['codUsuario'],
                    'monto'=>$request['solicitud']['monto'],
                    'motivo'=>$request['solicitud']['motivo'],
                    'fecha'=>Carbon::now(),
                    'estado'=>'PVC'
                ]); 
                $solicitud->save();
            }
            //Garante uno
            if($request['garante1']['codGarante']!=null)
            {
                $garanteSolicitudUno = new GaranteSolicitud([
                    'codSolicitud'=>$solicitud->codSolicitud,
                    'codSocio'=>$request['garante1']['codGarante']
                ]);
                $garanteSolicitudUno->save();
            }
            else
            {
                $garanteUno = new Socio([
                    'codDistrito' =>$request['garante1']['codDistrito'],
                    'dni'=>$request['garante1']['dni'],
                    'nombre' =>$request['garante1']['nombre'],
                    'apePaterno'=>$request['garante1']['apePaterno'],
                    'apeMaterno' =>$request['garante1']['apeMaterno'],
                    'fecNacimiento' =>$request['garante1']['fecNacimiento'],
                    'telefono'=>$request['garante1']['telefono'],
                    'domicilio' =>$request['garante1']['domicilio'],
                    'codDistrito'=>$request['garante1']['distrito'],
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
            if($request['garante2']['codGarante']!=null)
            {
                $garanteSolicitudDos = new GaranteSolicitud([
                    'codSolicitud'=>$solicitud->codSolicitud,
                    'codSocio'=>$request['garante2']['codGarante']
                ]);
                $garanteSolicitudDos->save();
            }
            else
            {
                $garanteDos = new Socio([
                    'codDistrito' =>$request['garante2']['codDistrito'],
                    'dni'=>$request['garante2']['dni'],
                    'nombre' =>$request['garante2']['nombre'],
                    'apePaterno'=>$request['garante2']['apePaterno'],
                    'apeMaterno' =>$request['garante2']['apeMaterno'],
                    'fecNacimiento' =>$request['garante2']['fecNacimiento'],
                    'telefono'=>$request['garante2']['telefono'],
                    'domicilio' =>$request['garante2']['domicilio'],
                    'codDistrito'=>$request['garante2']['distrito'],
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
        }
        catch (\Exception $e) 
        {
            DB::rollback();

            $mensaje = $e->getMessage();

            return response($mensaje, 500);
        }
    }

    public function ValidarTelefonoSocioGarante($telefono)
    {
        $consulta = Socio::where('socio.telefono','=',$telefono)
            ->count();

        return response()->json($consulta, 200);
    }

    public function ListarSolicitudesDia($codigo)
    {
        $fechaAyer=  Carbon::yesterday();

        $solicitudesDia = Solicitud::select('solicitud.codSolicitud','solicitud.codUsuario',
                        'solicitud.codSocio',DB::raw('FORMAT(solicitud.monto, 2) AS monto'),'solicitud.motivo','solicitud.fecha','solicitud.estado',
                        'socio.codSocio','socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno','socio.telefono')
                        ->join("usuario","usuario.codUsuario","solicitud.codUsuario")
                        ->join("socio","socio.codSocio","solicitud.codSocio")
                        ->where([
                            'solicitud.codUsuario'=>$codigo,
                            'solicitud.estado'=>'PVC'
                            ])
                        ->where('solicitud.fecha','>',date("Y-m-d H:i:s",strtotime($fechaAyer."+ 23 hours + 59 minutes + 59 seconds")))
                        ->where('solicitud.fecha','<',date("Y-m-d H:i:s",strtotime($fechaAyer."+ 2 days")))
                            ->get();
        return response()->json($solicitudesDia,200);
    }

    //Vamos a Anular una  solicitud que se encuentre en estado de Pendiente de Verificación Crediticia en la vista de Lista de Solicitudes Diaria
    public function AnularSolicitudPVC($id,Request $request)
    {
        $solicitud = Solicitud::findOrFail($id);

        $solicitud->estado = 'ANU';
        $solicitud->motAnulado = $request->get('motivo');
        $solicitud->save();

        return response()->json($solicitud,200);
    }

    public function ListarSolicitudesPendienteDeVerificacionCrediticia()
    {
        $data = array();

        // --------------------- SOLICITUDES CON TODAS LAS VERIFICACIONES APROBADAS --------------------    
        //Aqui conseguire las solicitudes que ya esten para aprobar osea que las tres verificaciones esten en AP (Aprobada)
        $consultaSA = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVC',
                                    ])
                                ->where(['verificar.v1' => 'AP','verificar.v2' => 'AP','verificar.v3' => 'AP'])
                                ->orderBy('solicitud.fecha','asc')
                                ->get();
        
        // --------------------- SOLICITUDES CON VERIFICACIONES NO APROBADAS --------------------                            
        //Aqui consigo las consultas que se van a rechazar, osea que tenga en cualquiera de sus verificaciones un NA (NO Aprobado)
        $consultaSNA = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVC',
                                ])
                                ->where(['verificar.v1' => 'NA','verificar.v2' => 'NA','verificar.v3' => 'NA'])
                                ->orderBy('solicitud.fecha','asc')
                                ->get();

        // --------------------- SOLICITUDES CON VERIFICACIONES SIN TERMINAR --------------------
        //Aqui se consiguen las solicitudes que en sus verificaciones tengan AP(Aprobado) pero no en los tres y NR(No Revisado)
        $consultaNAND = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVC',
                                ])
                                ->whereIn(
                                    'verificar.v1',['AP','NR']  
                                )
                                ->whereIn(
                                    'verificar.v2',['AP','NR']  
                                )
                                ->whereIn(
                                    'verificar.v3',['AP','NR']  
                                )
                                ->orderBy('solicitud.fecha','asc')
                                ->get();
            $diffA3A1 = array();
            $diffA3A1 = $consultaNAND->diff($consultaSA);

            // ------------------- SOLICITUDES SIN VERIFICACIONES -------------------
            // Aqui se obtienen las solicitudes que no tienes verificaciones realizadas
            $solicitudesV = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
            'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'verificar.estado'=>'PVC',
                ])
            ->orderBy('solicitud.fecha','asc')
            ->get();

            $solicitudesNoRevisadas = Solicitud::select(
                'solicitud.codSolicitud','solicitud.fecha',
                 DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'solicitud.estado'=>'PVC'
            ])
            ->orderBy('solicitud.fecha','asc')
            ->get();
            //Creo los array necesario para obtener la diferencia entre solicitudes totales y las solicitudes que estan en verificacion
            $a = array();
            $b = array();           
            $a = $solicitudesNoRevisadas->pluck('codSolicitud');
            $b = $solicitudesV->pluck('codSolicitud');
            $c = $a->diff($b);
            //Aqui busco con las keys del array $c, las solicitudes que aun no se ha hecho ninguna verificacion
            $solicitudesNR = Solicitud::select(
                'solicitud.codSolicitud','solicitud.fecha',
                 DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'solicitud.estado'=>'PVC'
            ])
            ->whereIn(
                'solicitud.codSolicitud',$c->all()
            )
            ->orderBy('solicitud.fecha','asc')
            ->get();

            //Indice de colleciones:
            // $consultaSA : Todas las solicitudes por Aprobar
            // $consultaSNA : Todas las solicitudes No Aprobadas
            // $diffA3A1 : Solicitudes que aun falten verificaciones
            // $solicitudesNR : Solicitudes que aun no tienen veririficaciones

            $data = [$consultaSA,$consultaSNA,$diffA3A1,$solicitudesNR];
            return response()->json($data,200);
           
        
    }

    public function ListarSolicitudesPendienteDeVerificacionDeDatos()
    {
        $data = array();

        // --------------------- SOLICITUDES CON TODAS LAS VERIFICACIONES APROBADAS --------------------    
        //Aqui conseguire las solicitudes que ya esten para aprobar osea que las tres verificaciones esten en AP (Aprobada)
        $consultaSA = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVD',
                                    ])
                                ->where(['verificar.v1' => 'AP','verificar.v2' => 'AP','verificar.v3' => 'AP'])
                                ->orderBy('solicitud.fecha','asc')
                                ->get();
        
        // --------------------- SOLICITUDES CON VERIFICACIONES NO APROBADAS --------------------                            
        //Aqui consigo las consultas que se van a rechazar, osea que tenga en cualquiera de sus verificaciones un NA (NO Aprobado)
        $consultaSNA = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVD',
                                ])
                                ->where(['verificar.v1' => 'NA','verificar.v2' => 'NA','verificar.v3' => 'NA'])
                                ->orderBy('solicitud.fecha','asc')
                                ->get();

        // --------------------- SOLICITUDES CON VERIFICACIONES SIN TERMINAR --------------------
        //Aqui se consiguen las solicitudes que en sus verificaciones tengan AP(Aprobado) pero no en los tres y NR(No Revisado)
        $consultaNAND = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
                                ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
                                ->join("socio","socio.codSocio","solicitud.codSocio")
                                ->where([
                                    'verificar.estado'=>'PVD',
                                ])
                                ->whereIn(
                                    'verificar.v1',['AP','NR']  
                                )
                                ->whereIn(
                                    'verificar.v2',['AP','NR']  
                                )
                                ->whereIn(
                                    'verificar.v3',['AP','NR']  
                                )
                                ->orderBy('solicitud.fecha','asc')
                                ->get();
            $diffA3A1 = array();
            $diffA3A1 = $consultaNAND->diff($consultaSA);

            // ------------------- SOLICITUDES SIN VERIFICACIONES -------------------
            // Aqui se obtienen las solicitudes que no tienes verificaciones realizadas
            $solicitudesV = Verificar::select('verificar.estado','verificar.codSolicitud','verificar.codVerificar',DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
            'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join('solicitud','solicitud.codSolicitud','verificar.codSolicitud')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'verificar.estado'=>'PVD',
                ])
            ->orderBy('solicitud.fecha','asc')
            ->get();

            $solicitudesNoRevisadas = Solicitud::select(
                'solicitud.codSolicitud','solicitud.fecha',
                 DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'solicitud.estado'=>'PVD'
            ])
            ->orderBy('solicitud.fecha','asc')
            ->get();
            //Creo los array necesario para obtener la diferencia entre solicitudes totales y las solicitudes que estan en verificacion
            $a = array();
            $b = array();           
            $a = $solicitudesNoRevisadas->pluck('codSolicitud');
            $b = $solicitudesV->pluck('codSolicitud');
            $c = $a->diff($b);
            //Aqui busco con las keys del array $c, las solicitudes que aun no se ha hecho ninguna verificacion
            $solicitudesNR = Solicitud::select(
                'solicitud.codSolicitud','solicitud.fecha',
                 DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
                'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
            ->join("socio","socio.codSocio","solicitud.codSocio")
            ->where([
                'solicitud.estado'=>'PVD'
            ])
            ->whereIn(
                'solicitud.codSolicitud',$c->all()
            )
            ->orderBy('solicitud.fecha','asc')
            ->get();

            //Indice de colleciones:
            // $consultaSA : Todas las solicitudes por Aprobar
            // $consultaSNA : Todas las solicitudes No Aprobadas
            // $diffA3A1 : Solicitudes que aun falten verificaciones
            // $solicitudesNR : Solicitudes que aun no tienen veririficaciones

            $data = [$consultaSA,$consultaSNA,$diffA3A1,$solicitudesNR];
            return response()->json($data,200);
    }

    public function ListarSolicitudesPreAprobadas(){
        $data = array();
        $solicitudesPA = Solicitud::select(
            'solicitud.codSolicitud','solicitud.fecha',
             DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
            'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
        ->join("socio","socio.codSocio","solicitud.codSocio")
        ->where([
            'solicitud.estado'=>'PAC'
        ])
        ->orderBy('solicitud.fecha','asc')
        ->get();

        $data = [$solicitudesPA];
            return response()->json($data,200);
    }

    public function ListarSolicitudesAprobadas(){
        $data = array();
        $solicitudesA = Solicitud::select(
            'solicitud.codSolicitud','solicitud.fecha',
             DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'),
            'socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno')
        ->join("socio","socio.codSocio","solicitud.codSocio")
        ->where([
            'solicitud.estado'=>'ACE'
        ])
        ->orderBy('solicitud.fecha','asc')
        ->get();

        $data = [$solicitudesA];
            return response()->json($data,200);
    }

    public function ConsultarDetalleSolicitudDeCredito($cod)
    {
        $data = array();

        $solicitud = Solicitud::select('solicitud.codSolicitud', 
            DB::raw('FORMAT(solicitud.monto, 2) AS monto'), 'solicitud.motivo', 'solicitud.fecha', 
            DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'), 'solicitud.estado', 
            'usuario.codTipoUsuario','usuario.codTrabajador','usuario.dni', 'usuario.activo', 
            'socio.codDistrito','socio.dni','socio.nombre','socio.apePaterno', 'socio.apeMaterno',
            'socio.fecNacimiento', 
            DB::raw('date_format(socio.fecNacimiento, "%d/%m/%Y") AS formatoFechaNacimiento'),
            'socio.telefono', 'socio.domicilio','socio.tipo', 'socio.activo', 
            'distrito.nombre AS distrito', 'provincia.nombre AS provincia', 
            'departamento.nombre AS departamento')
            ->join('usuario','solicitud.codUsuario','=','usuario.codUsuario')
            ->join('socio','solicitud.codSocio','=','socio.codSocio')
            ->join('distrito', 'socio.codDistrito','=','distrito.codDistrito')
            ->join('provincia', 'distrito.codProvincia','=','provincia.codProvincia')
            ->join('departamento', 'provincia.codDepartamento','=','departamento.codDepartamento')
            ->where('solicitud.codSolicitud', $cod)
            ->first();

        $garantes = GaranteSolicitud::select('garantesolicitud.codGaranteSolicitud', 
            'socio.codDistrito','socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno',
            'socio.fecNacimiento','socio.telefono','socio.domicilio','socio.tipo','socio.activo')
            ->join('socio','garantesolicitud.codSocio','=','socio.codSocio')
            ->where('garantesolicitud.codSolicitud',$cod)
            ->get();

        $verificacion = Verificar::select('v1','v2','v3','v4')
            ->where('codSolicitud',$cod)
            ->where('estado',$solicitud->estado)
            ->first();

        $data = [$solicitud,$garantes,$verificacion];
        
        return response()->json($data,200);
    }

    public function ConsultarDetalleSolicitudAprobada($cod)
    {
        $data = array();

        $solicitud = Solicitud::select('solicitud.codSolicitud', 
            DB::raw('FORMAT(solicitud.monto, 2) AS monto'), 'solicitud.motivo', 'solicitud.fecha', 
            DB::raw('date_format(solicitud.fecha, "%d/%m/%Y") AS formatoFecha'), 'solicitud.estado', 
            'usuario.codTipoUsuario','usuario.codTrabajador','usuario.dni', 'usuario.activo', 
            'socio.codDistrito','socio.dni','socio.nombre','socio.apePaterno', 'socio.apeMaterno',
            'socio.fecNacimiento', 
            DB::raw('date_format(socio.fecNacimiento, "%d/%m/%Y") AS formatoFechaNacimiento'),
            'socio.telefono', 'socio.domicilio','socio.tipo', 'socio.activo', 
            'distrito.nombre AS distrito', 'provincia.nombre AS provincia', 
            'departamento.nombre AS departamento')
            ->join('usuario','solicitud.codUsuario','=','usuario.codUsuario')
            ->join('socio','solicitud.codSocio','=','socio.codSocio')
            ->join('distrito', 'socio.codDistrito','=','distrito.codDistrito')
            ->join('provincia', 'distrito.codProvincia','=','provincia.codProvincia')
            ->join('departamento', 'provincia.codDepartamento','=','departamento.codDepartamento')
            ->where('solicitud.codSolicitud', $cod)
            ->first();

        $garantes = GaranteSolicitud::select('garantesolicitud.codGaranteSolicitud', 
            'socio.codDistrito','socio.dni','socio.nombre','socio.apePaterno','socio.apeMaterno',
            'socio.fecNacimiento','socio.telefono','socio.domicilio','socio.tipo','socio.activo')
            ->join('socio','garantesolicitud.codSocio','=','socio.codSocio')
            ->where('garantesolicitud.codSolicitud',$cod)
            ->get();

        $verificaciones = Verificar::select('verificar.v1','verificar.v2','verificar.v3','verificar.v4','verificar.estado')
            ->where('verificar.codSolicitud',$cod)
            ->whereIn('verificar.estado',['PVC','PVD'])
            ->get();

        $data = [$solicitud,$garantes,$verificaciones];
        
        return response()->json($data,200);
    }
    
    public function AprobarSolicitudPVC(Request $request)
    {
        try 
        {
            $verificacionesCumplidas = Verificar::select('v1','v2','v3')
                ->where('codSolicitud',$request['codSolicitud'])
                ->where('estado','PVC')
                ->first();
            $solicitudPVC = Solicitud::find($request['codSolicitud']);
            
            if($solicitudPVC->estado=='PVC' && $verificacionesCumplidas['v1'] == 'AP' && $verificacionesCumplidas['v2'] == 'AP' && $verificacionesCumplidas['v3'] == 'AP')
            {
                $solicitudPVC->estado = 'PVD';
                $solicitudPVC->save();
                return response()->json( "Actualizado a PVD" ,200);
            }
            return response()->json($verificacionesCumplidas);
        } 
        catch (\Exception $e) 
        {
            return $e->getMessage();
        } 
    }
    public function RechazarSolicitudPVC(Request $request)
    {
        $solicitudPVC = Solicitud::find($request['codSolicitud']);
        $varfija = ($request['codSolicitud']);

        if($solicitudPVC->estado=='PVC')
        {
            $solicitudPVC->estado = 'REC';
            $solicitudPVC->motRechazo = $request['motivo'];
            $solicitudPVC->save();
            
            $verificacionPVC = Verificar::where(
                'verificar.codSolicitud', $varfija
            )
            ->update(['estado'=>'REC']);
           
            return response()->json("Actualizado a Rechazado" ,200);
        }
    }

    public function AprobarSolicitudPVD(Request $request)
    {
        try 
        {
            $verificacionesCumplidas = Verificar::select('v1','v2','v3')
                ->where('codSolicitud',$request['codSolicitud'])
                ->where('estado','PVD')
                ->first();
            $solicitudPVC = Solicitud::find($request['codSolicitud']);
            
            if($solicitudPVC->estado=='PVD' && $verificacionesCumplidas['v1'] == 'AP' && $verificacionesCumplidas['v2'] == 'AP' && $verificacionesCumplidas['v3'] == 'AP')
            {
                $solicitudPVC->estado = 'PAC';
                $solicitudPVC->save();
                return response()->json( "Actualizado a PAC" ,200);
            }
            return response()->json($verificacionesCumplidas);
        } 
        catch (\Exception $e) 
        {
            return $e->getMessage();
        } 
    }
    
    public function RechazarSolicitudPVD(Request $request)
    {
        $solicitudPVC = Solicitud::find($request['codSolicitud']);
        $varfija = ($request['codSolicitud']);
        if($solicitudPVC->estado=='PVD')
        {
            $solicitudPVC->estado = 'REC';
            $solicitudPVC->motRechazo = $request['motivo'];
            $solicitudPVC->save();
            $verificacionPVC = Verificar::where(
                'verificar.codSolicitud', $varfija
            )
            ->update(['estado'=>'REC']);
            return response()->json("Actualizado a Rechazado" ,200);
        }
    }

    public function AprobarSolicitudPAC(Request $request)
    {
        $data = array();
        try 
        {
            $verificacionesCumplidasPVC = Verificar::select('v1','v2','v3')
                ->where('codSolicitud',$request['codSolicitud'])
                ->where('estado','PVC')
                ->first();
            $verificacionesCumplidasPVD = Verificar::select('v1','v2','v3')
                ->where('codSolicitud',$request['codSolicitud'])
                ->where('estado','PVD')
                ->first();
            $solicitudPVC = Solicitud::find($request['codSolicitud']);
            
            if(($solicitudPVC->estado=='PVC' && $verificacionesCumplidasPVC['v1'] == 'AP' && $verificacionesCumplidasPVC['v2'] == 'AP' && $verificacionesCumplidasPVC['v3'] == 'AP')&&
                ($solicitudPVC->estado=='PVD' && $verificacionesCumplidasPVD['v1'] == 'AP' && $verificacionesCumplidasPVD['v2'] == 'AP' && $verificacionesCumplidasPVD['v3'] == 'AP'))
            {
                $solicitudPVC->estado = 'ACE';
                $solicitudPVC->save();
                return response()->json( "Actualizado a ACE" ,200);
            }
            $data = [$verificacionesCumplidasPVC,$verificacionesCumplidasPVD];
            return response()->json($data);
        } 
        catch (\Exception $e) 
        {
            return $e->getMessage();
        } 
    }
    
    public function RechazarSolicitudPAC(Request $request)
    {
        $solicitudPVC = Solicitud::find($request['codSolicitud']);

        if($solicitudPVC->estado=='PAC')
        {
            $solicitudPVC->estado = 'REC';
            $solicitudPVC->motRechazo = $request['motivo'];
            $solicitudPVC->save();
            return response()->json("Actualizado a Rechazado" ,200);
        }
    }
}
