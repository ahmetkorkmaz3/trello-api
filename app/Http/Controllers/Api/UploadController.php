<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\UploadResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function __invoke(UploadRequest $request): JsonResponse
    {
        $file = auth()->user()->addMedia($request->file)->toMediaCollection();
        return $this->successResponse(UploadResource::make($file), 'Success');
    }
}
