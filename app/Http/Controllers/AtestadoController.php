<?php

namespace App\Http\Controllers;

use App\Http\Requests\Atestado\AtestadoCreateRequest;
use App\Http\Requests\Atestado\ListAtestadoOcorrenciasRequest;
use App\Models\Atestado;
use App\Models\CnaeEmpresa;
use App\Models\Funcionario;
use App\Models\RelacaoAtestadoCid;
use App\Models\RelacaoAtestadoOcorrencia;
use App\Models\RelacaoUsuarioEmpresa;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AtestadoController extends Controller
{
    use Authenticate, FormatData, ResponseMessage;

    private $atestado;
    private $relAtestadoCid;
    private $funcionario;
    private $relCnaeEmpre;
    private $relAtestadoOcorrencia;
    private $relUserEmpre;

    public function __construct()
    {
        $this->atestado = new Atestado();
        $this->relAtestadoCid = new RelacaoAtestadoCid();
        $this->funcionario = new Funcionario();
        $this->relCnaeEmpre = new CnaeEmpresa();
        $this->relAtestadoOcorrencia = new RelacaoAtestadoOcorrencia();
        $this->relUserEmpre = new RelacaoUsuarioEmpresa();
    }

    public function create(AtestadoCreateRequest $request)
    {
        $token = $this->decodeToken($request);

        try {

            $funcionario = $this->funcionario
                ->first();

            dd($funcionario);

            $atestadoEmpreCnae = $this->relCnaeEmpre->getEmpreCnae($atestadoEmpre);
            $cnaeList = [];
            $dataCodigoCid = array_unique($data['codigo_cid']);

            foreach ($atestadoEmpreCnae as $cnae) {

                array_push($cnaeList, $cnae['codigo_cnae']);

            }

            $response = Http::get('http://localhost:8080/relationship/exists-group', [

                "cnaes" => $cnaeList,
                "cid10" => $dataCodigoCid,

            ]);

            if ($response['message'][0]['total'] != 0) {

                $data['ocorrencia'] = count($response['message'][0]['relationship']);
                $data['tratado'] = 0;

            } else {

                $data['ocorrencia'] = 0;
                $data['tratado'] = 1;

            }

            $this->atestado->create($data);

            $data['id_atestado'] = $this->atestado->getAtestadoId($data);

            if ($response['message'][0]['total'] != 0) {

                foreach ($response['message'][0]['relationship'] as $relationship) {

                    $this->relAtestadoOcorrencia->create([

                        'codigo_cid' => $relationship['codigo_cid'],
                        'codigo_cnae' => $relationship['codigo_cnae'],
                        'id_atestado' => $data['id_atestado'],

                    ]);

                };

            }

            foreach ($dataCodigoCid as $cid) {

                $this->relAtestadoCid->create([

                    'codigo_cid' => $cid,
                    'id_atestado' => $data['id_atestado'],

                ]);

            }

        } catch (Exception $e) {

            return $e;
            return $this->formateMenssageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMenssageSuccess("Atestado cadastro com sucesso");
    }

    public function listAtestadoOcorrencias(ListAtestadoOcorrenciasRequest $request)
    {
        $tokenUser = $this->decodeToken($request);
        $tokenEmpre = $this->relUserEmpre->getUserEmpre($tokenUser['id_usuario']);

        if ($tokenUser['id_tipo_usuario'] == 1) {

            $tokenAllEmpreFunc = $this->funcionario->getAllEmpreFunc($request['empresa']);

        } else {

            $tokenAllEmpreFunc = $this->funcionario->getAllEmpreFunc($tokenEmpre);

        }

        $listAtestadoOcorrencia = [];

        foreach ($tokenAllEmpreFunc as $func) {

            $atestadoOcorrencia = $this->atestado->getAtestado($func['id_funcionario']);

            foreach ($atestadoOcorrencia as $atestado) {

                if ($atestado['ocorrencia'] != 0) {

                    array_push($listAtestadoOcorrencia, $atestado);

                }
                
            }

        }

        return $listAtestadoOcorrencia;

    }

    public function countOccurrence(Request $request, $id_empresa)
    {
        $user = $this->decodeToken($request);

        dd($this->relacaoUsuarioEmpresas
                ->getRelationShip($user->id_usuario, $id_empresa)
                ->exists());
    }

}
