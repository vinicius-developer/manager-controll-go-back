<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Funcionario;
use App\Traits\Authenticate;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request;

class CheckEmployee
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
        $token = $this->decodeToken($request);

        $hasAcess = Funcionario::checkIfCompanyHasAccessEmployeeStatic($token->com, $request->funcionario)
            ->exists();

        if($hasAcess) {
            return $next($request);
        };

        return $this->formateMenssageError('Não foi possível concluir ação', 403);
    }
}
