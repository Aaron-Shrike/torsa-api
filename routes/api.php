<?php

use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/iniciar-sesion', [UsuarioController::class, 'iniciarSesion']);
Route::post('/cerrar-sesion', [UsuarioController::class, 'cerrarSesion']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/registrar-trabajador', [TrabajadorController::class, 'store']); 
    
    // Route::post('/registrar-solicitud', [, 'store']); 
});
