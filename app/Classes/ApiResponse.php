<?php

namespace App\Classes;

class ApiResponse
{
    public static function send($status, $message, $data = [], $error = false)
    {
        $success = ($status === 200) ? true : false;

        return response()->json([
            'success' => $success,
            'data'    => $data,
            'message' => $message,
            'errors'  => $error
        ], $status);
    }
}
