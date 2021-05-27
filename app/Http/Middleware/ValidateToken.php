<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Traits\Authenticate;
use Exception;
use Closure;

class ValidateToken
{

    use Authenticate;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        try {

            $this->checkToken($token);

        } catch(Exception $e) {

            return response()->json(['status' => false, 'errors' => 'Você não tem acesso a essa ação'], 401);

        }

        return $next($request);
    }
}
