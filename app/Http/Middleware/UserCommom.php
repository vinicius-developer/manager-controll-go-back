<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\Authenticate;
use App\Traits\ResponsaMessage;

class UserCommom
{
    use Authenticate, ResponsaMessage;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $this->decodeToken($request);

        if($user->id_tipo_usuario === 2) {

            return $next($request);

        }

        $this->formateMessageError('Seu usuário não consegue realizar essa ação', 401);
    }
}
