<?php

namespace App\Http\Controllers;

use App\Http\Requests\Empresa\DisableEmpresaRequest;
use App\Http\Requests\Empresa\CreateEmpresaRequest;
use App\Models\RelacaoUsuarioEmpresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseMessage;
use App\Traits\Authenticate;
use App\Models\CnaeEmpresa;
use App\Traits\ApiCnaeCid;
use App\Models\Empresa;
use App\Models\Usuario;
use Exception;

class EmpresaController extends Controller
{
    use Authenticate, ResponseMessage, ApiCnaeCid;

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

    /**
     * Função para criar empresa
     * 
     * @param CreateEmpresaRequest $request
     * @return JsonResponse
     */
    public function create(CreateEmpresaRequest $request): JsonResponse
    {
        $cnaesEmpresa = array_unique($request['cnae']);

        DB::beginTransaction();

        try {

            $id_empresa = $this->empresa->create([
                'cnpj' => $request->cnpj,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
            ])->id_empresa;


            $ids_cnaes = [];

            foreach ($cnaesEmpresa as $cnae) {

                $checkCnae = $this->findCnae($cnae);

                if ($checkCnae->serverError()) {
                    return $this->formateMenssageError("O cnae $cnae não foi encontrado", 500);
                };

                $id = $this->cnaeEmpresa->create([
                    'id_empresa' => $id_empresa,
                    'codigo_cnae' => $cnae,
                ])->id_cnae_empresa;

                $ids_cnaes[] = $id;

            }

            $id_usuario = $this->usuario->create([
                'nome' => $request->nome,
                'email' => $request->email,
                'id_tipo_usuario' => 2,
                'password' => $this->generatePassword($request->password)
            ])->id_usuario;

            $this->relacaoUsuarioEmpresa->create([
                'id_empresa' => $id_empresa,
                'id_usuario' => $id_usuario
            ]);


        } catch (Exception $e) {

            DB::rollBack();

            dd($e->getMessage());

            return $this->formateMenssageError("Não foi possível concluir o cadastramento", 500);

        }

        DB::commit();

        return $this->formateMenssageSuccess("Empresa cadastrada com sucesso", 201);

    }

    /**
     * Função para delete empresa
     * 
     * @param DisableEmpresaRequest $request
     * @return JsonResponse
     */
    public function delete(DisableEmpresaRequest $request): JsonResponse
    {
        try {

            $this->empresa
                ->getCompanyWithId($request->code)
                ->delete();

        } catch (Exception $e) {

            return $this->formateMenssageError("Não foi possivel fazer a exclusão do banco de dados", 500);

        }

        return $this->formateMenssageSuccess("Empresa desativada com sucesso");

    }

    /**
     * Função para listar as empresas
     * 
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {

        try {

            return $this->formateMenssageSuccess(
                $this->empresa
                ->select(
                    'id_empresa',
                    'nome_fantasia')
                ->paginate(10), 
                200);

        } catch(Exception $e) {

            return $this->formateMenssageError("Não foi possível selecionar a lista", 500);

        }

    }

    /**
     * Função de rollback para deletar as ações já concluidas
     * 
     * @param int $id_empresa
     * @param array $ids_cnaes
     * @param int $id_usuario
     * @return void
     */
    private function rollback(int $id_empresa = null, array $ids_cnaes = null, int $id_usuario = null): void
    {

        if($id_empresa)  {
            $this->empresa
                ->getCompanyWithId($id_empresa)
                ->delete();
        }

        if($ids_cnaes) {
            $this->cnaeEmpresa
                ->getWithIds($ids_cnaes)
                ->delete();
        }

        if($id_usuario) {
            $this->usuario
                ->getUserWithId($id_usuario)
                ->delete();
        }
    }

}
