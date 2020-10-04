<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\DestroyCardRequest;
use App\Http\Requests\Card\ShowCardRequest;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Http\Requests\Column\IndexColumnRequest;
use App\Http\Resources\Card\CardResource;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{
    /**
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Board $board, Column $column)
    {
        $this->authorize('view', $board);
        return $this->successResponse(CardResource::collection($column->cards), 'Card list', 200);
    }

    /**
     * @param StoreCardRequest $request
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreCardRequest $request, Board $board, Column $column)
    {
        $this->authorize('create', $board);
        try {
            $card = $column->cards()->create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        } catch (\Exception $exception) {
            return $this->errorResponse('Card could not be created', 500);
        }
        return $this->successResponse(CardResource::make($card), 'Card created successfully', 201);

    }

    /**
     * @param ShowCardRequest $request
     * @param Board $board
     * @param Column $column
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(ShowCardRequest $request, Board $board, Column $column, Card $card)
    {
        $this->authorize('show', $board);

        return $this->successResponse(CardResource::make($card), 'Card details', 200);
    }

    /**
     * @param UpdateCardRequest $request
     * @param Board $board
     * @param Column $column
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateCardRequest $request, Board $board, Column $column, Card $card)
    {
        $this->authorize('update', $board);

        try {
            $card->update($request->validated());
        } catch (\Exception $exception) {
            return $this->errorResponse('Card could not be updated!', 500);
        }
        return $this->successResponse(CardResource::make($card), 'Card updated successfully', 200);
    }

    /**
     * @param DestroyCardRequest $request
     * @param Board $board
     * @param Column $column
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyCardRequest $request, Board $board, Column $column, Card $card)
    {
        $this->authorize('forceDelete', $board);

        try {
            $card->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Card could not be deleted!', 500);
        }
        return $this->successResponse(null, 'Card deleted successfully', 200);
    }
}
