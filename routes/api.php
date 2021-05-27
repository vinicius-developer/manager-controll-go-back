<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;

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

Route::post('login', [UsuarioController::class, 'authenticate']);

Route::middleware(['checktoken'])->group(function() {

    Route::prefix('usuario')->group(function() {

        Route::post('create', [UsuarioController::class, 'store']);

    });
});
