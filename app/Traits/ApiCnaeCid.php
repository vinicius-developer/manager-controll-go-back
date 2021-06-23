<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ApiCnaeCid
{

    protected function findCnae($cnae)
    {
        $clearCnae = preg_replace(['(\D)', '(\W)'], '', $cnae);

        return Http::get(config('apis.api_cnae_cid') . "/cnae/find/$clearCnae");
    }

}
