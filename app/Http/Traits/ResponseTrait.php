<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param $data
     * @param null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, $message, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param $message
     * @param $code
     * @return JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
