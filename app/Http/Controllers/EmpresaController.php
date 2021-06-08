<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Models\Empresa;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;

class EmpresaController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

    private $empresa;

    public function  __contruct()
    {
        $this->empresa = new Empresa();
    }

    private $formatInsertEmpre = [

        'cnae' => 'cnae_empresa',

    ];

    public function create_empresa(CreateEmpresaRequest $request)
    {
        // $token = $this->generateToken(1, env( 'API_FRONT_MCG', 'http://127.0.0.1'));

        // $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertEmpre);

        // return '$data';

        return 'Ola';

    }
}
