<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Hash;
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

Route::post('login', [UsuarioController::class, 'authenticate']);

Route::middleware(['checktoken'])->group(function () {

    Route::prefix('usuario')->group(function () {

        Route::post('create', [UsuarioController::class, 'storeUser']);
        Route::post('createAdmin', [UsuarioController::class, 'storeUserAdmin']);

    });

    Route::prefix('empresa')->group(function () {

        Route::post('create', [EmpresaController::class, 'create_empresa']);
        Route::post('delete', [EmpresaController::class, 'disable_empresa']);

    });

});

// Gera uma senha encriptada para testar a api

Route::post('senhaTeste', function () {

    $Hsenha = Hash::make('Aa@123456', ['rounds' => 12]);
    return response()->json([
        'senha' => $Hsenha,
    ]);

});
