<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Traits\Authenticate;
use App\Traits\ResponseMessage;

class UserAdm
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

        if(Usuario::isAdmin($token->sub)->exists()) {

            return $next($request);

        }

        $this->formateMenssageError('Seu usuário não consegue realizar essa ação', 401);

    }
}
