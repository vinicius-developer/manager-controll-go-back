<?php

namespace App\Http\Controllers;

use App\Traits\Authenticate;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    use Authenticate;

    private $empresa;

    public function  __contruct()
    {
        $this->empresa = new Empresa();
    }

    public function create_empresa()
    {
        $token = $this->generateToken(1, env( 'API_FRONT_MCG', 'http://127.0.0.1'));




    }
}
