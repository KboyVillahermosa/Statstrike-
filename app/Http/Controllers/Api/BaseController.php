<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Return a success JSON response.
     */
    protected function success($data, ?string $message = null, int $status = 200): JsonResponse
    {
        $response = ['data' => $data];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        return response()->json($response, $status);
    }

    /**
     * Return an error JSON response.
     */
    protected function error(string $message, ?array $errors = null, int $status = 400): JsonResponse
    {
        $response = ['message' => $message];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response, $status);
    }
}
