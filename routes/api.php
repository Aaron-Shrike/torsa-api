<?php

use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TipoCargoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SocioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\ContactoEmergenciaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\TipoUsuarioController;
use App\Models\ContactoEmergencia;
use App\Models\TipoCargo;
use App\Models\TipoUsuario;

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
Route::get('/obtener-cargos', [TipoCargoController::class, 'ObtenerCargos']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/registrar-trabajador', [TrabajadorController::class, 'store']);
    // Route::post('/registrar-solicitud', [, 'store']);
});

Route::apiResource('tipocargo',TipoCargoController::class);

Route::apiResource('tipousuario',TipoUsuarioController::class);

Route::apiResource('contactoemergencias',ContactoEmergenciaController::class);

Route::apiResource('trabajador',TrabajadorController::class);

Route::apiResource('socio',SocioController::class);

Route::post('nuevo', 'App\Http\Controllers\UsuarioController@nuevo');
Route::post('validarDNI', 'App\Http\Controllers\UsuarioController@validarDNI');
Route::post('validarEmail', 'App\Http\Controllers\UsuarioController@validarEmail');
Route::get('buscarSocioGaranteHabilitado/{dni}', 'App\Http\Controllers\SocioController@buscarSocioGaranteHabilitado');
Route::post('registrarSolicitud','App\Http\Controllers\SolicitudController@registrarSolicitud');
Route::get('listarSolicitudesDia','App\Http\Controllers\SolicitudController@listarSolicitudesDia');
