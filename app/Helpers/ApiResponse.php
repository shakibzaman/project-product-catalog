<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($message, $errors = null, $status = 422)
    {
        return response()->json([
            'success' => false,
            'status' => $status,
            'message' => $message,
            'data' => [
                'error' => $errors,
            ],
        ], $status);
    }
}
