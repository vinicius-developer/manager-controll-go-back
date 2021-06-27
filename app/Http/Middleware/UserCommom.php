<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use App\Traits\Authenticate;
use App\Traits\ResponseMessage;

class UserCommom
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

        $user = Usuario::getUserWithIdStatic($token->sub)
            ->select('id_tipo_usuario')
            ->first();

        if($user->id_tipo_usuario === 2) {

            return $next($request);

        }

        $this->formateMenssageError('Seu usuário não consegue realizar essa ação', 401);
    }
}
