<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\CheckList\StoreCheckListRequest;
use App\Http\Requests\Card\CheckList\UpdateCheckListRequest;
use App\Models\Card;
use App\Models\CardCheckList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardCheckListController extends Controller
{
    /**
     * @param Card $card
     * @return JsonResponse
     */
    public function index(Card $card): JsonResponse
    {
        return $this->successResponse($card->checkLists, 'Success');
    }

    /**
     * @param StoreCheckListRequest $request
     * @param Card $card
     * @return JsonResponse
     */
    public function store(StoreCheckListRequest $request, Card $card): JsonResponse
    {
        try {
            $checklist = $card->checkLists()->create([
                'text' => $request->text,
            ]);
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('CheckList could not created', 500);
        }

        return $this->successResponse($checklist, 'Success', 201);
    }

    /**
     * @param UpdateCheckListRequest $request
     * @param CardCheckList $cardCheckList
     * @return JsonResponse
     */
    public function update(UpdateCheckListRequest $request, CardCheckList $cardCheckList): JsonResponse
    {
        try {
            $cardCheckList->update($request->validated());
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Checklist could not updated!', 500);
        }

        return $this->successResponse($cardCheckList, 'Success');
    }

    /**
     * @param CardCheckList $cardCheckList
     * @return JsonResponse
     */
    public function destroy(CardCheckList $cardCheckList): JsonResponse
    {
        try {
            $cardCheckList->delete();
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Checklist could not delete', 500);
        }

        return $this->successResponse(null, 'Success', 204);
    }
}
