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
use App\Traits\ResponsaMessage;
use Exception;
use Illuminate\Support\Facades\Http;

class AtestadoController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

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

    private $formatInsertAtestado = [
        'funcionario' => 'id_funcionario',
        'crm-medico' => 'crm_medico',
        'codigo-cid' => 'codigo_cid',
        'data-atestado' => 'data_lancamento',
        'data-termino' => 'termino_de_descanso',
    ];

    public function create(AtestadoCreateRequest $request)
    {
        $tokenUser = $this->decodeToken($request)->id_usuario;
        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertAtestado);
        $data['id_usuario'] = $tokenUser;

        try {

            $atestadoEmpre = $this->funcionario->getFuncEmpre($data['id_funcionario']);
            $atestadoEmpreCnae = $this->relCnaeEmpre->getEmpreCnae($atestadoEmpre);
            $cnaeList = [];
            $dataCodigoCid = array_unique(explode(', ', $data['codigo_cid']));

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

            }

            $this->atestado->create($data);

            $data['id_atestado'] = $this->atestado->getAtestadoId($data);

            if ($response['message'][0]['total'] != 0) {

                foreach($response['message'][0]['relationship'] as $relationship){

                    $this->relAtestadoOcorrencia->create([

                        'codigo_cid' => $relationship['codigo_cid'],
                        'codigo_cnae' => $relationship['codigo_cnae'],
                        'id_atestado' => $data['id_atestado']

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

        }

        return $this->formateMessageSuccess("Atestado cadastro com sucesso");

    }

    public function listAtestadoOcorrencias(ListAtestadoOcorrenciasRequest $request){

        $tokenUser = $this->decodeToken($request);

        return $this->relUserEmpre->getUserEmpre($tokenUser['id_usuario']);

    }

}
