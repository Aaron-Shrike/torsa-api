<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Usuario;
use App\Models\ContactoEmergencia;
use App\Models\Trabajador;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
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

    public function nuevo(Request $request){
        //dd(request()->all());
        $alt = Str::random(10);

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
            //CREAMOS EL CONTACTO DE EMERGENCIA
            $cemergencias = new ContactoEmergencia([
                'nombre'=>$request->get('nombreC'),
                'numero'=>$request->get('numero'),
                'parentesco'=>$request->get('parentesco')
            ]);
    
            $cemergencias->save();
    
            //CREAMOS EL TRABAJADOR
            $trabajador = new Trabajador([
                'codConEmergencia'=>$cemergencias->codConEmergencia,
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
                'codTipoUsuario'=>$request->get('codTipoUsuario'),
                'codTrabajador'=>$trabajador->codTrabajador,
                'dni'=>$request->get('dni'),
                'contrasenia'=>Hash::make($alt),
                'activo'=>1,

            ]);
            $usuario->save();
            DB::commit();
                 
            $receivers = Trabajador::all('correo')->max('correo');

            Mail::to($receivers)->send(new TestMail($alt));
            return "Correo Electronico Enviado";
        
        } catch (\Exception $e) {
        DB::rollback();
        return $e->getMessage();
        }
          
           
    }

    public function validarDNI(Request $request){
        $request->validate([
            'dni'=>'required'
        ]);
        $consulta = Usuario::where('usuarios.dni','=',$request->dni)
            ->count();
        return response()->json($consulta, 200);
    }

    public function validarEmail(Request $request){
        $request->validate([
            'correo'=>'required'
        ]);
        $consulta = Trabajador::where('trabajadors.correo','=',$request->correo)
            ->count();
        return response()->json($consulta, 200);
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
                                    'codigo' => $consulta['codUsuario'],
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
}
