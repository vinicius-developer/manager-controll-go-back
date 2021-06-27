<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\DisableEmpresaRequest;
use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Models\RelacaoUsuarioEmpresa;
use App\Traits\ResponseMessage;
use App\Traits\Authenticate;
use App\Models\CnaeEmpresa;
use App\Traits\ApiCnaeCid;
use App\Traits\FormatData;
use App\Models\Empresa;
use App\Models\Usuario;
use Exception;

class EmpresaController extends Controller
{
    use Authenticate, FormatData, ResponseMessage, ApiCnaeCid;

    private $relacaoUsuarioEmpresa;
    private $cnaeEmpresa;
    private $empresa;
    private $usuario;


    public function __construct()
    {
        $this->relacaoUsuarioEmpresa = new RelacaoUsuarioEmpresa();
        $this->cnaeEmpresa = new CnaeEmpresa();
        $this->empresa = new Empresa();
        $this->usuario = new Usuario();
    }

    public function createEmpresa(CreateEmpresaRequest $request)
    {
        $cnaesEmpresa = array_unique($request['cnae']);

        try {

            $idEmpresa = $this->empresa->create([
                'cnpj' => $request->cnpj,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
            ])->id_empresa;

        } catch (Exception $e) {

            return $this->formateMenssageError("CNPJ já está em uso, não pode ser repetido", 500);

        }

        try {

            $this->insertCnae($idEmpresa, $cnaesEmpresa);

        } catch (Exception $e) {

            return $this->formateMenssageError('Erro ao tentar inserir o CNAE da empresa', 500);

        }

        try {

            $idUsuario = $this->usuario->create([
                'nome' => $request->nome,
                'email' => $request->email,
                'id_tipo_usuario' => 2,
                'password' => $this->generatePassword($request->password)
            ])->id_usuario;

        } catch(Exception $e) {

            return $this->formateMenssageError('Não foi possível inserir o primeiro usuário', 500);

        }

        try {

            $this->relacaoUsuarioEmpresa->create([
                'id_empresa' => $idEmpresa,
                'id_usuario' => $idUsuario
            ]);

        } catch(Exception $e) {

            return $this->formateMenssageError('Não foi possível criar relação entre usuário e empresa', 500);

        }

        return $this->formateMenssageSuccess("Empresa cadastrada com sucesso", 201);

    }

    public function disableEmpresa(DisableEmpresaRequest $request)
    {

        $tokenUser = $this->decodeToken($request);

        if ($tokenUser->id_tipo_user == 2) {

            return $this->formateMenssageError('O usuario não tem autorização para desativar uma empresa', 401);

        }

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertEmpre);

        try {

            $this->empresa->getEmpreWithCnpj($data['cnpj'])->delete();

        } catch (Exception $e) {

            return $this->formateMenssageError("Não foi possivel fazer a exclusão do banco de dados", 500);

        }

        return $this->formateMenssageSuccess("Empresa desativada com sucesso");

    }

    public function insertCnae($idEmpresa, $cnaes)
    {
        foreach ($cnaes as $cnae) {

            $checkCnae = $this->findCnae($cnae);

            if ($checkCnae->serverError()) {
                return $this->formateMenssageError("O cnae $cnae não foi encontrado", 500);
            };

            $this->cnaeEmpresa->create([
                'id_empresa' => $idEmpresa,
                'codigo_cnae' => $cnae,
            ]);

        }
    }

}
