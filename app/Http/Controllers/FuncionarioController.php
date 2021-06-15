<?php

namespace App\Http\Controllers;

use App\Http\Requests\Funcionarios\CreateFuncionarioRequest;
use App\Http\Requests\Funcionarios\DeleteFuncionarioRequest;
use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\RelacaoUsuarioEmpresa;
use App\Models\Usuario;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;

class FuncionarioController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

    private $empresa;
    private $usuario;
    private $usuarioEmpre;
    private $funcionario;

    public function __construct()
    {
        $this->empresa = new Empresa();
        $this->usuario = new Usuario();
        $this->usuarioEmpre = new RelacaoUsuarioEmpresa();
        $this->funcionario = new Funcionario();

    }

    private $formatInsertFunc = [
        'nome_funcionario' => 'nome',
        'empresa' => 'id_empresa',
        'funcionario' => 'id_funcionario',
    ];

    public function createFuncionario(CreateFuncionarioRequest $request)
    {

        $data = $this->tokenRequestAddEmpresa($request);

        $data = $this->checkSintaxeWithReference($data->all(), $this->formatInsertFunc);

        if ($data['id_empresa'] == 0) {

            return $this->formateMessageError("Informe a empresa do funcionário que deseja cadastrar", 500);

        }

        $empreSituation = $this->empresa->checkEmpreIsActive($data['id_empresa']);

        if ($empreSituation == 0) {

            return $this->formateMessageError("Não será possivel efetuar o cadastro, empresa inativa", 500);

        }

        try {

            $this->funcionario->create($data);

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possível cadastrar o funcionário", 500);

        }

        return $this->formateMessageSuccess("Funcionário cadastrado com sucesso");

    }

    public function deleteFuncionario(DeleteFuncionarioRequest $request)
    {

        $tokenEmpresa = $this->tokenRelUserEmpre($request);
        $funcEmpresa = $this->consultFuncEmpre($request['funcionario']);

        if (isset($tokenEmpresa)) {
            if ($tokenEmpresa['id_empresa'] != $funcEmpresa['id_empresa']) {

                return $this->formateMessageError("Funcionario não encontrado", 500);

            }
        }

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertFunc);

        try {

            $this->funcionario->getFuncId($data['id_funcionario'])->first()->delete();

        } catch (Exception $e) {

            return $e;

        }

        return $this->formateMessageSuccess("Funcionario deletado com sucesso");

    }

    private function tokenRelUserEmpre($request)
    {

        $token = $request->bearerToken();
        $tokenUserId = $this->checkToken($token)->sub;
        return $this->usuarioEmpre->getReUserEmpre($tokenUserId)->first();

    }

    private function consultFuncEmpre($funcionario)
    {

        return $this->funcionario->getFuncId($funcionario)->first();

    }

    private function tokenRequestAddEmpresa($request)
    {

        $token = $request->bearerToken();
        $tokenUserId = $this->checkToken($token)->sub;
        $relUserEmpre = $this->usuarioEmpre->getReUserEmpre($tokenUserId);

        if (count($relUserEmpre) == 0) {

            $request['id_usuario'] = $this->usuario->getUserWithId($tokenUserId)->value('id_usuario');

        } else {

            $request['empresa'] = $relUserEmpre[0]['id_empresa'];
            $request['id_usuario'] = $relUserEmpre[0]['id_usuario'];

        }

        return $request;

    }

}
