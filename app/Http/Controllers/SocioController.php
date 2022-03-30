<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Solicitud;
use App\Models\GaranteSolicitud;
use App\Models\Verificar;
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
    public function BuscarGaranteHabilitado($dni)
    {
        try
        {
            $data=array();
            $error = 
            [
                'error' => true,
                'mensaje' => ""
            ];
            //Busca socio por el dni
            $socio = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno","socio.apeMaterno",
                    "socio.fecNacimiento","socio.telefono","socio.domicilio","socio.tipo", "socio.activo")
                    ->where([
                        "socio.dni"=>$dni,
                    ])
                    ->first();

            if(isset($socio['dni']))
            {  
                if($socio['activo'] == "1")
                {
                    $data = 
                    [
                        'codSocio' => $socio['codSocio'],
                        'dni' => $socio['dni'],
                        'nombre'=> $socio['nombre'],
                        'apePaterno'=> $socio['apePaterno'],
                        'apeMaterno'=> $socio['apeMaterno'],
                        'fecNacimiento'=> $socio['fecNacimiento'],
                        'telefono'=> $socio['telefono'],
                        'domicilio'=> $socio['domicilio'],
                        'tipo'=> $socio['tipo'],
                        'activo'=> $socio['activo'],
                    ]; 
                    $verificaGarante = GaranteSolicitud::select('solicitud.estado','garantesolicitud.codSocio','solicitud.fecha')
                    ->join('solicitud','solicitud.codSolicitud','garantesolicitud.codSolicitud')
                    ->join('socio','socio.codSocio','garantesolicitud.codSocio')
                    ->where('garantesolicitud.codSocio','=',$socio['codSocio'])
                    ->orderBy('solicitud.fecha','desc')
                    ->first();
                    if(isset($verificaGarante['codSocio']))
                    {
                        if($verificaGarante['estado']=='REC' or $verificaGarante['estado']=='ANU')
                        {

                            return response($data);
                        } 
                        else
                        {
                            $error = 
                            [
                                'estado' =>  $verificaGarante['estado'],
                                'fecha' =>  $verificaGarante['fecha'],
                            ];
                            $error['mensaje'] = "Es garante de una solicitud pendiente.";
                            return response($error);
                        }           
                    }
                    else
                    {
                        return response($data);
                    }
                
                }
                else
                {
                    $error['mensaje'] = $socio['tipo'] ." inactivo.";

                    return response($error); 
                }
            }
            else
            {
                $error['mensaje'] = "DNI no registrado. ¡Ingrese los datos!";
                $error['error'] = false;

                return response($error);
            } 

        }
        catch(\Exception $e)
        {
            $mensaje = $e->getMessage();

            return response($mensaje, 500);
        }

    }
    public function BuscarSocioGaranteHabilitado($dni)
    {
        try
        {
            $data=array();
            $error = 
            [
                'error' => true,
                'mensaje' => ""
            ];

            //Busca socio por el dni
            $socio = Socio::select("socio.codSocio","socio.dni","socio.nombre","socio.apePaterno","socio.apeMaterno",
                    "socio.fecNacimiento","socio.telefono","socio.domicilio","socio.tipo", "socio.activo")
                    ->where([
                        "socio.dni"=>$dni,
                    ])
                    ->first();

            if(isset($socio['dni']))
            {  
                if($socio['activo'] == "1")
                {
                    $data = 
                    [
                        'codSocio' => $socio['codSocio'],
                        'dni' => $socio['dni'],
                        'nombre'=> $socio['nombre'],
                        'apePaterno'=> $socio['apePaterno'],
                        'apeMaterno'=> $socio['apeMaterno'],
                        'fecNacimiento'=> $socio['fecNacimiento'],
                        'telefono'=> $socio['telefono'],
                        'domicilio'=> $socio['domicilio'],
                        'tipo'=> $socio['tipo'],
                        'activo'=> $socio['activo'],
                    ]; 
                    //Busca codigo socio en la tabla solicitud
                    $verificaSocio = Socio::select('solicitud.estado','socio.codSocio','solicitud.fecha')
                            ->join('solicitud','solicitud.codSocio','socio.codSocio')
                            ->where('socio.codSocio','=',$socio['codSocio'])
                            ->orderBy('solicitud.fecha','desc')
                            ->first();
                    if(isset($verificaSocio['codSocio']))
                    {   
                        if($verificaSocio['estado']=='REC' or $verificaSocio['estado']=='ANU')
                        {
                            //Busca codigo del socio en la tabla garantesolicitud
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
                                    return response($data);
                                } 
                                else
                                {
                                    $error['mensaje'] = "Es garante de una solicitud pendiente.";

                                    return response($error);
                                }           
                            }
                            else
                            {
                                return response($data);
                            }
                        }
                        else
                        {
                            $fechita = $verificaSocio['solicitud.fecha'];
                            switch ($verificaSocio['estado']) {
                                case 'PVC':
                                    $error = 'Tiene una solicitud registrada con fecha: ' . $fechita. ' que se encuentra pendiente de verificación creditica ';
                                    break;
                                case 'PVD':
                                    $error = 'Tiene una solicitud registrada con fecha: ' . $fechita. 'que se encuentra pendiente de verificación de datos';
                                    break;
                                case 'PAC':
                                    $error = 'Tiene una solicitud registrada con fecha: ' . $fechita. 'que se encuentra pendiente de aprobación de crédito';
                                    break;
                                case 'ACE':
                                    $error = 'Su solicitud registrada con fecha: ' . $fechita. 'se encuentra aceptada';
                                    break;
                                
                                default:
                                    $error = 'Ale Garcia estuvo aqui ajajjajajajaja';
                                    break;
                            }
                           
                            return response($error); 
                        }
                    }
                    else
                    {
                        //Busca codigo del socio en la tabla garantesolicitud
                        $contador = GaranteSolicitud::select('solicitud.estado','garantesolicitud.codSocio')
                        ->join('solicitud','solicitud.codSolicitud','garantesolicitud.codSolicitud')
                        ->join('socio','socio.codSocio','garantesolicitud.codSocio')
                        ->where('garantesolicitud.codSocio','=',$socio['codSocio'])
                        ->where('solicitud.estado' == 'PVC' || 'PVD' || 'ACE' || 'PAC')
                        ->count();

                        $verificaGarante = GaranteSolicitud::select('solicitud.estado','garantesolicitud.codSocio')
                                ->join('solicitud','solicitud.codSolicitud','garantesolicitud.codSolicitud')
                                ->join('socio','socio.codSocio','garantesolicitud.codSocio')
                                ->where('garantesolicitud.codSocio','=',$socio['codSocio'])
                                ->orderBy('solicitud.fecha','desc')
                                ->first();
                                
                        switch ($contador) {
                            case '0':
                                return response($data);
                                break;
                            case '1':
                                return response($data);
                                break;
                            case '2':   
                                $vercasa = Verificar::join("solicitud","solicitud.codSolicitud","verificar.codSolicitud")
                                ->select("v3")
                                ->get();

                                break;
                            
                            default:
                                # code...
                                break;
                        }

                       

                        if(isset($verificaGarante['codSocio']))
                        {
                            if($verificaGarante['estado']=='REC' or $verificaGarante['estado']=='ANU')
                            {
                                return response($data);
                            }   
                            else
                            {
                                $error['mensaje'] = "Es garante de una solicitud pendiente.";
                                return response($error);
                            }         
                        }
                        else
                        {
                            return response($data); 
                        }
                    }
                }
                else
                {
                    $error['mensaje'] = $socio['tipo'] ." inactivo.";

                    return response($error); 
                }
            }
            else
            {
                $error['mensaje'] = "DNI no registrado. ¡Ingrese los datos!";
                $error['error'] = false;

                return response($error);
            }
        }
        catch(\Exception $e)
        {
            $mensaje = $e->getMessage();

            return response($mensaje, 500);
        }
    }
}
