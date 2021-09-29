<?php

namespace App\Http\Controllers;

use App\Http\Requests\Funcionarios\CreateFuncionarioRequest;
use App\Http\Requests\Funcionarios\DeleteFuncionarioRequest;
use App\Models\RelacaoUsuarioEmpresa;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;
use App\Traits\Authenticate;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Usuario;
use Exception;

class FuncionarioController extends Controller
{
    use Authenticate, ResponseMessage;

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

    public function create(CreateFuncionarioRequest $request)
    {
        $token = $this->decodeToken($request);

        try {

            $this->funcionario->create([
                'nome' => $request->nome_funcionario,
                'cargo' => $request->cargo,
                'id_empresa' => $token->com,
                'id_usuario' => $token->sub
            ]); 

        } catch (Exception $e) {

            return $this->formateMenssageError("Não foi possível cadastrar o funcionário", 500);

        }

        return $this->formateMenssageSuccess("Funcionário cadastrado com sucesso");

    }

    public function delete(DeleteFuncionarioRequest $request)
    {
        $token = $this->decodeToken($request);

        try {

            $this->funcionario
                ->getFuncId($request->funcionario, $token->com)
                ->delete();

        } catch (Exception $e) {

            return $this->formateMenssageError('Não foi possível deletar funcionário', 404);

        }

        return $this->formateMenssageSuccess("Funcionario deletado com sucesso");

    }

    public function list(Request $request)
    {
        $token = $this->decodeToken($request);
        
        $employees = $this->funcionario
            ->getAllEmployeeCompanies($token->com)
            ->select(
                'id_funcionario',
                'nome',
                'cargo',
                'created_at'
            )
            ->paginate(10);
            

        return $this->formateMenssageSuccess($employees);
    }

    public function listWithOcurrence(Request $request)
    {
        $token = $this->decodeToken($request);
        
        $employees = $this->funcionario
            ->getAllEmployeeCompanies($token->com)
            ->join('atestados as a',
                'a.id_funcionario',
                '=',
                'funcionarios.id_funcionario'
            )
            ->select(
                'funcionarios.id_funcionario',
                'nome',
                'cargo',
                'funcionarios.created_at'
            )
            ->where('a.ocorrencia', '>', 0)
            ->where('a.tratado', 0)
            ->paginate(10);

        return $this->formateMenssageSuccess($employees);
    }
}
