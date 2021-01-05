<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class BoardActivityController extends Controller
{
    /**
     * @param Board $board
     * @return JsonResponse
     */
    public function __invoke(Board $board): JsonResponse
    {
        return $this->successResponse($board->activities, 'Success');
    }
}
