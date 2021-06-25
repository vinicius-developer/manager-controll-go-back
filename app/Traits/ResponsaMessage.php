<?php

namespace App\Traits;

trait ResponsaMessage
{

    public function formateMessageError($message, $code)
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

    public function formateMessageSuccess($message, $code = 200)
    {
        return response()->json([
            'status' => true, 
            'message' => $message
        ], $code);

    }
}
