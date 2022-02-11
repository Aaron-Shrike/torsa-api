<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
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

    public function IniciarSesion(Request $request)
    {
        $data = array();

        try {
            $request->validate([
                'dni' => 'required',
                'contrasenia' => 'required',
            ]);

            $usuario = Usuario::where('dni', $request->dni)->first(['dni', 'contrasenia','tipoUsuario','activo']);

            if(isset($usuario['dni'])){
                if($usuario['contrasenia'] == $request->contrasenia && $usuario['activo']){
                    $data = [
                        'usuario' => $usuario,
                    ];
                }else{
                    $mensaje = 'El usuario no esta activo';

                    $data = [
                        'error' => true,
                        'mensaje' => $mensaje
                    ];
                }
            }else{
                $mensaje = 'El usuario no esta registrado';

                $data = [
                    'error' => true,
                    'mensaje' => $mensaje
                ];
            }

            return response($data);
        } catch (\Exception $ex) {
            $data = $ex->getMessage();

            return response($data, 400);
        }
    }
}
