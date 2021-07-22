<?php

namespace App\Http\Middleware;

use App\Traits\ResponseMessage;
use App\Traits\Authenticate;
use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Atestado;
use Closure;

class HasPermissionOcurrence
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

        $path = explode('/', $request->path());
        
        $employee = Atestado::getCertificateStatic($path[count($path) - 1])
            ->value('id_funcionario');

        $permission = Funcionario::checkIfCompanyHasAccessEmployeeStatic($token->com, $employee)
            ->exists();
        
        if($permission) {
            return $next($request);
        }

        $this->formateMenssageError('Você não tem acesso a essa ação', 403);
    }
}
