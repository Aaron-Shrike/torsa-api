<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Usuario;
use App\Models\ContactoEmergencia;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{

    //Inicio de sesion
    public function IniciarSesion(Request $request)
    {
        $data=array();
        try
        {
            $request->validate([
                'dni' => 'required',
                'contrasenia' => 'required',
            ]);

            $consulta = Usuario::join('tipousuario','usuario.codTipoUsuario','=','tipousuario.codTipoUsuario') 
                        -> join('trabajador','usuario.codTrabajador','=','trabajador.codTrabajador') 
                        ->select('usuario.codUsuario','usuario.dni','usuario.contrasenia','tipousuario.descripcion','usuario.activo','trabajador.nombre','trabajador.apePaterno','trabajador.apeMaterno')
                        ->where('usuario.dni','=',$request->dni)
                        ->first();

            if(isset($consulta['dni']))
            {
                if($consulta['activo'])
                {   
                    if(Hash::check($request->contrasenia, $consulta['contrasenia']))
                    {
                        $data = [
                            'dni' => $consulta['dni'],
                            'codUsuario' => $consulta['codUsuario'],
                            'tipoUsuario' => $consulta['descripcion'],
                            'nombre'=> $consulta['nombre'],
                            'apePaterno'=> $consulta['apePaterno'],
                            'apeMaterno'=> $consulta['apeMaterno'],
                        ];

                    }   
                    else
                    {
                        $mensaje = 'Contraseña no válida';
    
                        $data = [
                            'error' => true,
                            'mensaje' => $mensaje
                        ];
                    }
                }
                else
                {
                    $mensaje = 'Cuenta inactiva';

                    $data = [
                        'error' => true,
                        'mensaje' => $mensaje
                    ];
                }
            }
            else
            {
                $mensaje = 'El DNI no se encuentra registrado como usuario';

                $data = [
                    'error' => true,
                    'mensaje' => $mensaje
                ];
            }
            
            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manuales = Usuario::select("manual.codmanual", "manual.nombre", "pdf", "video", "manual.descripcion")
                ->join("manual_empresa", "manual.codmanual", "manual_empresa.codmanual")
                ->get();

        return response()->json($manuales, 200);
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

    public function Nuevo(Request $request){
        //dd(request()->all());
        

        $request->validate([
            'nombreC'=>'required',
            'numero'=>'required',
            'parentesco'=>'required',
            'nombreT'=>'required',
            'apePaterno'=>'required',
            'apeMaterno'=>'required',
            'fecNacimiento'=>'required',
            'telefono'=>'required',
            'domicilio'=>'required',
            'correo'=>'required|email',
            'codTipoCargo'=>'required',
            'dni'=>'required',
            'activo'=>'required'
        ]);
        DB::beginTransaction();
        try {

            $alt = Str::random(10);

            //CREAMOS EL CONTACTO DE EMERGENCIA
            $contactoEmergencia = new ContactoEmergencia([
                'nombre'=>$request->get('nombreC'),
                'numero'=>$request->get('numero'),
                'parentesco'=>$request->get('parentesco')
            ]);
    
            $contactoEmergencia->save();
    
            //CREAMOS EL TRABAJADOR
            $trabajador = new Trabajador([
                'codConEmergencia'=>$contactoEmergencia->codConEmergencia,
                'codTipoCargo'=>$request->get('codTipoCargo'), 
                'nombre'=>$request->get('nombreT'),
                'apePaterno'=>$request->get('apePaterno'),
                'apeMaterno'=>$request->get('apeMaterno'),
                'fecNacimiento'=>$request->get('fecNacimiento'),
                'telefono'=>$request->get('telefono'),
                'domicilio'=>$request->get('domicilio'),
                'correo'=>$request->get('correo'), 
            ]);
            $trabajador->save();
    
            //CREAMOS EL USUARIO
            $usuario = new Usuario([
                // 'codTipoUsuario'=>$request->get('codTipoUsuario'),
                'codTipoUsuario'=>$request->get('codTipoCargo'),
                'codTrabajador'=>$trabajador->codTrabajador,
                'dni'=>$request->get('dni'),
                'contrasenia'=>Hash::make($alt),
                'activo'=>1,

            ]);
            $usuario->save();
            
            DB::commit();
    
            $receivers = $trabajador->correo;
            Mail::to($receivers)->send(new TestMail($alt));
            return "Correo Electronico Enviado";
        
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function ValidarDNI(Request $request){
        $request->validate([
            'dni'=>'required'
        ]);
        $consulta = Usuario::where('usuario.dni','=',$request->dni)
            ->count();
        return response()->json($consulta, 200);
    }

    public function ValidarEmail(Request $request){
        $request->validate([
            'correo'=>'required'
        ]);
        $consulta = Trabajador::where('trabajador.correo','=',$request->correo)
            ->count();
        return response()->json($consulta, 200);
    }
}
