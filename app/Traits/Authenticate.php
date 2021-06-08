<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT as FirebaseJWT;

trait Authenticate
{
    /**
     * Checa um hash e uma string para ver se são
     * iguais
     *
     * @param string $string
     * @param string $hash
     * @return string
     */
    public function checkPassword(string $string, string $hash): string
    {
        return Hash::check($string, $hash);
    }

    /**
     * cria uma senha criptografada em bcrypt
     *
     * @param string $string
     * @return string
     */
    public function generatePassword(string $string): string
    {
        return Hash::make($string, ['rounds' => 12]);
    }

    /**
     * Gerar o token de acesso JWT
     *
     * @param int $id
     * @param string $aud
     * @return string
     */
    public function generateToken(int $id, string $aud):string
    {
        $assign = config('jwtAssing.assing');

        $now = time();

        $payload = [
            'sub' => $id,
            'iat' => $now,
            'exp' => $now + 18000,
            'aud' => $aud,
        ];

        $jwt = FirebaseJWT::encode($payload, $assign);

        return $jwt;
    }

    /**
     * Checa se o token é valido
     *
     * @param string $token
     */
    public function checkToken(string $token)
    {
        return FirebaseJWT::decode($token, config('jwtAssing.assing'), array('HS256'));
    }
}
