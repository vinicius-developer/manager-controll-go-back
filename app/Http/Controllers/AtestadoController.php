<?php

namespace App\Http\Controllers;

use App\Http\Requests\Atestado\AtestadoCreateRequest;
use App\Traits\Authenticate;
use App\Models\Atestado;

class AtestadoController extends Controller
{
    use Authenticate;

    private $atestado;

    public function __construct()
    {
        $this->atestado = new Atestado();
    }

    public function create(AtestadoCreateRequest $request)
    {
         $user = $this->decodeToken($request);

         dd($user);
    }



}
