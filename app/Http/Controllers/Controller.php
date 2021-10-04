<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function currentDate($format)
    {
        $unixDateTime = time();

        return date($format, $unixDateTime);
    }

    protected function getHeadersCsv($fileName)
    {
        return [
            "Content-type"        => "text/csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Content-Disposition" => "attachment; filename=relatorio_$fileName",
            "Expires"             => "0"
        ];
    }

}
