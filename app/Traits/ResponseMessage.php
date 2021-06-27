<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseMessage
{

    /**
     * Formata mensagem padrão de error
     * 
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function formateMenssageError(string $message, int $code): JsonResponse
    {
        return response()->json([
            'status' => false,
            'errors' => [
                'error' => [
                    $message
                ]
            ]
        ], $code);
    }

    /**
     * Formata mensagem padrão de sucesso
     * 
     * @param mixed $message
     * @param int $code
     * @param JsonResponse
     */
    public function formateMenssageSuccess(mixed $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true, 
            'message' => $message
        ], $code);

    }
}
