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

Route::post('login-admin', [UsuarioController::class, 'authenticate']);

Route::post('login', [UsuarioController::class, 'authenticateUser']);

Route::post('set-company', [UsuarioController::class, 'setCompany'])
    ->middleware('usercommom');

Route::middleware('checktoken')->group(function () {

    Route::middleware('companyset')->group(function () {

        Route::prefix('usuario')->group(function () {

            Route::post('create', [UsuarioController::class, 'storeUser']);

            Route::get('list', [UsuarioController::class, 'listUser']);
    
        });

    });

    Route::middleware('useradmin')->group(function () {

        Route::prefix('usuario')->group(function () {

            Route::post('create-admin', [UsuarioController::class, 'storeUserAdmin']);

        });

    });

});


Route::middleware(['checktoken'])->group(function () {

    Route::prefix('atestado')->group(function () {

        Route::post('create', [AtestadoController::class, 'create']);

        Route::get('listAtestadoOcorrencias', [AtestadoController::class, 'listAtestadoOcorrencias']);

        Route::get('count-occurrence/{id_empresa}', [AtestadoController::class, 'countOccurrence']);

    });

    Route::prefix('empresa')->group(function () {

        Route::post('create-admin', [EmpresaController::class, 'createEmpresa'])->middleware('useradmin');

        Route::post('delete', [EmpresaController::class, 'disableEmpresa']);

    });

    Route::prefix('funcionario')->group(function () {

        Route::post('create', [FuncionarioController::class, 'createFuncionario']);

        Route::post('delete', [FuncionarioController::class, 'deleteFuncionario']);

        Route::middleware(['usercommom'])->group(function () {

            Route::get('list', [FuncionarioController::class, 'listFuncionario']);
        });
    });

});
