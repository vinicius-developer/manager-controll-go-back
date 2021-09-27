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

        return Http::get($this->url . "/cnae/find/$clearCnae");
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
        $route = 'http://' . config('apis.cnae_cid');
        $route .= '/relationship/exists-group';

        return Http::get($route, [

            "cnaes" => $cnaeList,

            "cid10" => $cidList,

        ]);
    }

    protected function findCid(string $cid): Response 
    {
        $clearCid = preg_replace('/([.])/', '', $cid);

        $route = 'http://' . config('apis.cnae_cid');
        $route .= "/cid/find/$clearCid";

        return Http::get($route);
    }

    protected function findCids(array $cids): Response
    {
        foreach($cids as $cid) {

            $resultResponse = $this->findCid($cid);

            $cidExists = $resultResponse
                ->collect()['message']['exists'];

            if(!$cidExists) {
                return $resultResponse;
            }

        }

        return $resultResponse;
    }

}
