<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Http\Requests\Empresa\DisableEmpresaRequest;
use App\Models\CnaeEmpresa;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Traits\ApiCnaeCid;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;

class EmpresaController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage, ApiCnaeCid;

    private $cnaeEmpresa;
    private $empresa;
    private $usuario;

    public function __construct()
    {
        $this->empresa = new Empresa();
        $this->cnaeEmpresa = new CnaeEmpresa();
        $this->usuario = new Usuario();
    }


    public function createEmpresa(CreateEmpresaRequest $request)
    {
        $tokenUser = $this->decodeToken($request);

        try {

            $idEmpresa = $this->empresa->create([
                'cnpj' => $request->cnpj,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social
            ])->id_empresa;

            $cnaesEmpresa = array_unique($request->cnae);

            $this->insertCnae($idEmpresa, $cnaesEmpresa);

        } catch (Exception $e) {

            dd($e);

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

    public function insertCnae($idEmpresa, $cnaes)
    {
        foreach ($cnaes as $cnae) {

            $checkCnae = $this->findCnae($cnae);

            if($checkCnae->serverError()){
                return $this->formateMessageError("O cnae $cnae não foi encontrado", 500);
            };

            $this->cnaeEmpresa->create([
                'id_empresa' => $idEmpresa,
                'codigo_cnae' => $cnae,
            ]);

        }
    }


}
