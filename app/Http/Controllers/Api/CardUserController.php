<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\User\DestroyCardUserRequest;
use App\Http\Requests\Card\User\StoreCardUserRequest;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CardUserController extends Controller
{
    /**
     * @param Card $card
     * @return JsonResponse
     */
    public function index(Card $card): JsonResponse
    {
        return $this->successResponse($card->assignedUsers, 'Success');
    }

    /**
     * @param StoreCardUserRequest $request
     * @param Card $card
     * @return JsonResponse
     */
    public function update(StoreCardUserRequest $request, Card $card): JsonResponse
    {
        $card->assignedUsers()->attach($request->users);
        return $this->successResponse($card->assignedUsers, 'success');
    }

    /**
     * @param DestroyCardUserRequest $request
     * @param Card $card
     * @return JsonResponse
     */
    public function destroy(DestroyCardUserRequest $request, Card $card): JsonResponse
    {
        $card->assignedUsers()->detach($request->users);
        return $this->successResponse($card->assignedUsers, 'Success');
    }
}
