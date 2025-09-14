<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Sends a successful JSON response.
     */
    public function sendSuccess($data = null, string $message = 'success', int $code = 200): jsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Sends an error JSON response.
     */
    public function sendError($data = null, string $message = 'error', int $code = 400): jsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
