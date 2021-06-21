<?php

use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\AtestadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
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

    Route::prefix('atestado')->group(function() {

        Route::post('create', [AtestadoController::class, 'create']);
        Route::get('listAtestadoOcorrencias', [AtestadoController::class, 'listAtestadoOcorrencias']);        

        Route::get('count-occurrence/{id_empresa}', [AtestadoController::class, 'countOccurrence']);

    });

    Route::prefix('usuario')->group(function () {

        Route::post('create', [UsuarioController::class, 'storeUser']);

        Route::post('createAdmin', [UsuarioController::class, 'storeUserAdmin']);

        Route::get('list', [UsuarioController::class, 'listUser']);

    });

    Route::prefix('empresa')->group(function () {

        Route::middleware(['useradmin'])->group(function () {

            Route::post('create', [EmpresaController::class, 'createEmpresa']);

        });


        Route::post('delete', [EmpresaController::class, 'disableEmpresa']);

    });

    Route::prefix('funcionario')->group(function(){

        Route::post('create', [FuncionarioController::class, 'createFuncionario']);

        Route::post('delete', [FuncionarioController::class, 'deleteFuncionario']);

    });

});


