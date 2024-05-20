<?php

namespace App\Traits;

use Illuminate\Contracts\Support\MessageBag;

trait HttpResponses
{
    public function success(string $message, array $data, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error(string $message, array|MessageBag $errors, int $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
