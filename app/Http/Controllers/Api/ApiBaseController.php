<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiBaseController extends Controller
{
    public function successResponse(array $data, int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'ok', 'data' => $data], $status, $headers);
    }

    public function errorResponse(string $message, int $status, array $data = [], array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message, 'data' => $data], $status, $headers);
    }
}
