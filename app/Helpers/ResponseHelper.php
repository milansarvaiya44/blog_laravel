<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function errorResponse($errors, $statusCode = 422)
    {
        return response()->json([
            'error' => true,
            'message' => $errors
        ], $statusCode);
    }

    public static function successResponse($data, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
