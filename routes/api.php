<?php

use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TcargoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers;
use App\Http\Controllers\CemergenciaController;
use App\Models\Cemergencia;
use App\Models\Tcargo;
use App\Models\Tusuario;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas API
Route::post('/iniciar-sesion', [UsuarioController::class, 'IniciarSesion']);
Route::post('/cerrar-sesion', [UsuarioController::class, 'cerrarSesion']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/registrar-trabajador', [TrabajadorController::class, 'store']); 
    
    // Route::post('/registrar-solicitud', [, 'store']); 


});

Route::apiResource('tcargos',TcargoController::class);

Route::apiResource('cemergencias',CemergenciaController::class);

Route::apiResource('trabajadors',TrabajadorController::class);

Route::post('usuarios', 'App\Http\Controllers\UsuarioController@nuevo');

Route::apiResource('tusuarios',Tusuario::class);