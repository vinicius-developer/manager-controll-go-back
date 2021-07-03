<?php

namespace App\Http\Middleware;

use App\Models\RelacaoUsuarioEmpresa;
use App\Traits\ResponseMessage;
use App\Traits\Authenticate;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Exception;
use Closure;

class CompanySet
{
    use Authenticate, ResponseMessage;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {

            $token = $this->decodeToken($request);

        } catch (Exception $e) {

            return $this->formateMenssageError('Você não tem acesso ao a essa ação', 401);

        }


        try {

            if(!Empresa::checkEmpreIsActiveStatic($token->com)->exists()) {

                return $this->formateMenssageError('Você não tem acesso a essa ação', 403);

            }

        } catch (Exception $e) {

            return $this->formateMenssageError('Empresa invalida', 403);

        }

        try {

            if(RelacaoUsuarioEmpresa::getRelationShipStatic($token->sub, $token->com)) {

                return $next($request);

            }


        } catch (Exception $e) {

            return $this->formateMenssageError('Não foi possível concluir ação', 401);

        }            

    }
}
