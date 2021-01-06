<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\User\DestroyCardUserRequest;
use App\Http\Requests\Card\User\StoreCardUserRequest;
use App\Http\Resources\Card\CardAssignedUserResource;
use App\Models\Card;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CardAssignController extends Controller
{
    /**
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Card $card): JsonResponse
    {
        $this->authorize('view', $card->board());
        return $this->successResponse(CardAssignedUserResource::collection($card->assignedUsers), 'Success');
    }

    /**
     * @param StoreCardUserRequest $request
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(StoreCardUserRequest $request, Card $card): JsonResponse
    {
        $this->authorize('update', $card->board());
        $card->assignedUsers()->attach($request->users);
        return $this->successResponse(CardAssignedUserResource::collection($card->assignedUsers), 'success');
    }

    /**
     * @param DestroyCardUserRequest $request
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyCardUserRequest $request, Card $card): JsonResponse
    {
        $this->authorize('delete', $card->board());
        $card->assignedUsers()->detach($request->users);
        return $this->successResponse(CardAssignedUserResource::collection($card->assignedUsers), 'Success');
    }
}
