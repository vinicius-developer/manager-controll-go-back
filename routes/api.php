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

        Route::prefix('funcionario')->group(function () {

            Route::post('create', [FuncionarioController::class, 'create']);

            Route::delete('delete', [FuncionarioController::class, 'delete']);

            Route::get('list', [FuncionarioController::class, 'list']);

        });

        Route::prefix('atestado')->group(function () {

            Route::post('create', [AtestadoController::class, 'create'])->middleware('checkemployee');

            Route::get('get-all-certificate/{year}', [AtestadoController::class, 'getAllCertificateCompany']);
    
            Route::get('list-ocurrence', [AtestadoController::class, 'listOcurrence']);
    
            Route::get('count-occurrence', [AtestadoController::class, 'countOccurrence']);

            Route::get('info-occurrence/{id_ocurrence}', [AtestadoController::class, 'getInfoOcurrence'])->middleware('haspermissionocurrence');

            Route::get('get-certificate', [AtestadoController::class, 'getAllCertificateCompany']);

            Route::patch('treat-occurrence/{id_ocurrence}', [AtestadoController::class, 'treatOccurrence'])->middleware('haspermissionocurrence');
    
        });

    });

    Route::middleware('useradmin')->group(function () {

        Route::prefix('usuario')->group(function () {

            Route::post('create-admin', [UsuarioController::class, 'storeUserAdmin']);

        });

        Route::prefix('empresa')->group(function () {

            Route::post('create', [EmpresaController::class, 'create']);
    
            Route::delete('delete', [EmpresaController::class, 'delete']);

            Route::get('list', [EmpresaController::class, 'list']);
    
        });

    });

});
