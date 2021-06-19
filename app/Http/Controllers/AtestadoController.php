<?php

namespace App\Http\Controllers;

use App\Http\Requests\Atestado\AtestadoCreateRequest;
use App\Models\Atestado;
use App\Models\CnaeEmpresa;
use App\Models\Funcionario;
use App\Models\RelacaoAtestadoCid;
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

    public function __construct()
    {
        $this->atestado = new Atestado();
        $this->relAtestadoCid = new RelacaoAtestadoCid();
        $this->funcionario = new Funcionario();
        $this->relCnaeEmpre = new CnaeEmpresa;
    }

    private $formatInsertAtestado = [
        'funcionario' => 'id_funcionario',
        'crm-medico' => 'crm_medico',
        'codigo-cid' => 'codigo_cid',
        'data-atestado' => 'data_lancamento',
        'data-termino' => 'termino_de_descanco',
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
            $dataCodigoCid = explode(', ', $data['codigo_cid']);


            foreach($atestadoEmpreCnae as $cnae){

                array_push($cnaeList, $cnae['codigo_cnae']);
                
            }

            $response = Http::get('http://localhost:8080/relationship/exists-group', [

                "cnaes" => $cnaeList,
                "cid10" => $dataCodigoCid

            ]);

            $this->atestado->create($data);

            $data['id_atestado'] = $this->atestado->getAtestadoId($data);

            if($response['message'][0]['total'] != 0){

                


            }

            $this->relAtestadoCid->create($data);

        } catch (Exception $e) {

            return $e;

        }

        return "cadastrado";

    }

}
