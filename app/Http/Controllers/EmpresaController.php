<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Http\Requests\Empresa\DisableEmpresaRequest;
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

    private $cnaeEmpresa;
    private $empresa;
    private $usuario;

    public function __construct()
    {
        $this->empresa = new Empresa();
        $this->cnaeEmpresa = new CnaeEmpresa();
        $this->usuario = new Usuario();
    }

    private $formatInsertEmpre = [

        'cnae' => 'cnae_empresa',

    ];

    public function createEmpresa(CreateEmpresaRequest $request)
    {

        $tokenUser = $this->decodeToken($request);

        if ($tokenUser->id_tipo_user == '2') {

            return $this->formateMessageError('O usuario não tem autorização para cadastrar empresas', 401);

        }

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

    public function disableEmpresa(DisableEmpresaRequest $request)
    {

        $tokenUser = $this->decodeToken($request);

        if ($tokenUser->id_tipo_user == 2) {

            return $this->formateMessageError('O usuario não tem autorização para desativar uma empresa', 401);

        }

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertEmpre);

        try {

            $this->empresa->getEmpreWithCnpj($data['cnpj'])->delete();

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possivel fazer a exclusão do banco de dados", 500);

        }

        return $this->formateMessageSuccess("Empresa desativada com sucesso");

    }


}
