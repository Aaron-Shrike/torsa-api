<?php

use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TipoCargoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SocioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\ContactoEmergenciaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\VerificarController;
use App\Models\Departamento;
use App\Models\Solicitud;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Ruta de ejemplo, necesita autenticacion
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Rutas API

//Ruta para iniciar sesion
Route::post('/iniciar-sesion', [UsuarioController::class, 'IniciarSesion']);
Route::post('/cerrar-sesion', [UsuarioController::class, 'CerrarSesion']);

// Registrar Solicitud
Route::get('/buscar-garante-habilitado/{dni}', [SocioController::class, 'BuscarSocioGaranteHabilitado']);
Route::get('/validar-telefono/{dni}', [SolicitudController::class, 'ValidarTelefonoSocioGarante']);
Route::post('/registrar-solicitud', [SolicitudController::class, 'RegistrarSolicitud']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/registrar-trabajador', [TrabajadorController::class, 'store']);
    // Route::post('/registrar-solicitud', [, 'store']);
});

//Creacion de rutas de los crud de las tablas
Route::apiResource('tipocargo',TipoCargoController::class);
Route::apiResource('tipousuario',TipoUsuarioController::class);
Route::apiResource('contactoemergencias',ContactoEmergenciaController::class);
Route::apiResource('trabajador',TrabajadorController::class);
Route::apiResource('socio',SocioController::class);
Route::apiResource('solicitud',SolicitudController::class);
Route::apiResource('departamento',DepartamentoController::class);
Route::apiResource('provincia',ProvinciaController::class);
Route::apiResource('distrito',DistritoController::class);

//Rutas de UsuarioController
Route::post('nuevo', 'App\Http\Controllers\UsuarioController@Nuevo');
Route::post('validarDNI', 'App\Http\Controllers\UsuarioController@ValidarDNI');
Route::post('validarEmail', 'App\Http\Controllers\UsuarioController@ValidarEmail');

//Rutas de SolicitudController
Route::get('/listarSolicitudesDia/{codigo}','App\Http\Controllers\SolicitudController@ListarSolicitudesDia');
Route::post('/solicitud-pendiente-verificacion-crediticia', [SolicitudController::class, 'ListarSolicitudesPendienteDeVerificacionCrediticia']);
Route::get('/obtener-departamentos', [DepartamentoController::class,'ObtenerDepartamento']);
Route::get('/obtener-provincias/{codDepartamento}', [ProvinciaController::class,'ObtenerProvincias']);
Route::get('/obtener-distritos/{codProvincia}', [DistritoController::class,'ObtenerDistritos']);
Route::post('/verificaciones-solicitud',[VerificarController::class,'VerificacionesSolicitud']);
Route::post('/anularSolicitudPVC/{codigo}','App\Http\Controllers\SolicitudController@AnularSolicitudPVC');
Route::post('/consultarDetalleSolicitud/{codigo}','App\Http\Controllers\SolicitudController@ConsultarDetalleSolicitudDeCredito');
Route::post('/aceptar-solicitud-pvc', [SolicitudController::class, 'AceptarSolicitudPVC']);
Route::post('/rechazar-solicitud-pvc', [SolicitudController::class, 'RechazarSolicitudPVC']);