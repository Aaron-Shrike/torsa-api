<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use App\Models\ContactoEmergencia;
use App\Models\Trabajador;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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
        $cemergencias = new ContactoEmergencia([
            'nombre'=>$request->get('nombreC'),
            'numero'=>$request->get('numero'),
            'parentesco'=>$request->get('parentesco')
        ]);

         $cemergencias->save();


        // $request->validate([

        //]);
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

        //$request->validate([

        //]);
             $usuario = new Usuario([
                'codTipoUsuario'=>$request->get('codTipoUsuario'),
                'codTrabajador'=>$trabajador->codTrabajador,
                 'dni'=>$request->get('dni'),
                 'contrasenia'=>Crypt::encryptString(Str::random(10)),
                 'activo'=>1,
                 
                 //'secret' => Crypt::encryptString($request->secret)
             ]);
             $usuario->save();
            
             $receivers = Trabajador::all('correo')->max('correo');

             Mail::to($receivers)->send(new TestMail($usuario));
             return "Correo Electronico Enviado";
             
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
        $consulta = Usuario::join('tusuarios','usuarios.codTipoUsuario','=','tusuarios.codTipoUsuario')
                    ->select('usuarios.dni','usuarios.contrasenia','tusuarios.descripcion','usuarios.activo')
                    ->where('usuarios.dni','=',$request->dni)
                    ->first();
        if(isset($consulta['dni']))
        {
            if($consulta['activo'])
            {
                if($consulta['contrasenia'] == $request->contrasenia)
                {
                    //if($consulta['descripcion'] == 'Promotor')
                    //{
                            $data = [
                                'consulta' => $consulta,
                            ];
                    //}
                    //else
                    //{
                      //      $mensaje = 'Usuario no es promotor';

                        //    $data = [
                          //      'error' => true,
                            //    'mensaje' => $mensaje
                            //];
                    //}
                }
                else
                {
                        $mensaje = 'Contraseña no coincide con el usuario';

                        $data = [
                            'error' => true,
                            'mensaje' => $mensaje
                        ];
                }
            }
            else
            {
                $mensaje = 'Cuenta inactiva no podrá iniciar sesión';

                $data = [
                    'error' => true,
                    'mensaje' => $mensaje
                ];
            }
        }
        else
        {
            $mensaje = 'El dni no se encuentra registrado como usuario';

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
