<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

trait ApiCnaeCid
{

    /**
     *  Função que busca na API se cnae realmente existe
     * 
     * @param string $cnae
     * @return Response
     */
    protected function findCnae(string $cnae): Response
    {
        $clearCnae = preg_replace(['(\D)', '(\W)'], '', $cnae);

        return Http::get(config('apis.cnae_cid') . "/cnae/find/$clearCnae");
    }


    /**
     * Função que busca em um grupo $cnae se um grupo de cids tem relação
     * com algum deles
     * 
     * @param array $cnaeList
     * @param array $cidList
     * @return Response
     */
    protected function existsRelationInGroup(array $cnaeList, array $cidList): Response
    {
        return Http::get(config('apis.cnae_cid') . '/relationship/exists-group', [

            "cnaes" => $cnaeList,

            "cid10" => $cidList,

        ]);
    }

}
