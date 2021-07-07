<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\Atestado;
use App\Traits\ApiCnaeCid;
use App\Models\CnaeEmpresa;
use App\Models\Funcionario;
use App\Traits\Authenticate;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use App\Models\RelacaoAtestadoCid;
use App\Models\RelacaoUsuarioEmpresa;
use App\Models\RelacaoAtestadoOcorrencia;
use App\Http\Requests\Atestado\AtestadoCreateRequest;
use App\Http\Requests\Atestado\ListAtestadoOcorrenciasRequest;

class AtestadoController extends Controller
{
    use Authenticate, ResponseMessage, ApiCnaeCid;

    private $atestado;
    private $funcionario;
    private $cnaeEmpresa;
    private $relUserEmpre;
    private $relAtestadoCid;
    private $relAtestadoOcorrencia;

    public function __construct()
    {
        $this->atestado = new Atestado();
        $this->funcionario = new Funcionario();
        $this->cnaeEmpresa = new CnaeEmpresa();
        $this->relAtestadoCid = new RelacaoAtestadoCid();
        $this->relUserEmpre = new RelacaoUsuarioEmpresa();
        $this->relacaoAtestadoOcorrencia = new RelacaoAtestadoOcorrencia();
    }

    public function create(AtestadoCreateRequest $request)
    {
        $token = $this->decodeToken($request);

        $cids = array_unique($request->codigo_cid);

        $cnaes = $this->cnaeEmpresa
            ->getCompanyCnaes($token->com)
            ->get()
            ->pluck('codigo_cnae')
            ->toArray();         

        try {

            $responseApi = $this->existsRelationInGroup($cnaes, $cids);

            $ocurrence = isset($responseApi->collect()['message']['total']) ? $responseApi->collect()['message']['total'] : 0;

            $id_atestado = $this->atestado->create([
                'crm_medico' => $request->crm_medico,
                'id_funcionario' => $request->funcionario,
                'data_lancamento' => $request->data_atestado,
                'termino_de_descanso' => $request->data_termino,
                'ocorrencia' => $ocurrence,
                'id_usuario' => $token->sub
            ])->id_atestado;

            if($ocurrence) {

                $this->createRelacaoAtestadoOcorrencia($id_atestado, $responseApi->collect()['message']['relationship']);

            }

        } catch(Exception $e) {

            return $this->formateMenssageError('Não foi possível concluir a ação', 500);

        }

        return $this->formateMenssageSuccess('Atestado criado com sucesso', 201);

    }

    public function listAtestadoOcurrence(ListAtestadoOcorrenciasRequest $request)
    {
        $token = $this->decodeToken($request);

    }

    public function countOccurrence(Request $request, $id_empresa)
    {
        $user = $this->decodeToken($request);

        dd($this->relacaoUsuarioEmpresas
                ->getRelationShip($user->id_usuario, $id_empresa)
                ->exists());
    }

    private function createRelacaoAtestadoOcorrencia(int $id_atestado, array $items)
    {
        foreach($items as $item) {

            $this->relacaoAtestadoOcorrencia->create([
                'id_atestado' => $id_atestado,
                'codigo_cid' => $item['codigo_cid'],
                'codigo_cnae' => $item['codigo_cnae']
            ]);

        }
    }

}
