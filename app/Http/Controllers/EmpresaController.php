<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Models\CnaeEmpresa;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;

class EmpresaController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

    private $empresa;
    private $cnaeEmpresa;

    public function __construct()
    {
        $this->empresa = new Empresa();
        $this->cnaeEmpresa = new CnaeEmpresa();
        $this->usuario = new Usuario();
    }

    private $formatInsertEmpre = [

        'cnae' => 'cnae_empresa',

    ];

    public function create_empresa(CreateEmpresaRequest $request)
    {
        // $token = $this->generateToken(1, env( 'API_FRONT_MCG', 'http://127.0.0.1'));

        // Decodifica o token e identifica o tipo do usuario

        $token = $request->bearerToken();
        $decoded = $this->checkToken($token)->sub;
        $tokenTipoUser = $this->usuario::where('id_usuario', $decoded)->value('id_tipo_usuario');

        if($tokenTipoUser != '1'){

            return $this->formateMessageError('O usuario não tem autorização para o cadastro de empresas', 401);

        }

        // Formata e insere as informações do request em um banco de dados

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertEmpre);

        try {

            $this->empresa->create($data);
            $idEmpre = $this->empresa::where('cnpj', $data['cnpj'])->value('id_empresa');
            $cnaeEmpre = array_unique(explode(', ', $data['cnae_empresa']));

            foreach ($cnaeEmpre as $cnae) {

                $cnaeData = [
                    'id_empresa' => $idEmpre,
                    'codigo_cnae' => $cnae,
                ];

                $this->cnaeEmpresa->create($cnaeData);

            }

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMessageSuccess("Empresa cadastrada com sucesso");

    }
}
