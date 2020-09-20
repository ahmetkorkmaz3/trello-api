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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * @param IndexColumnRequest $request
     * @param Column $column
     * @return JsonResponse
     */
    public function index(IndexColumnRequest $request, Column $column)
    {
        return $this->successResponse(CardResource::collection($column->cards), 'Card list', 200);
    }

    /**
     * @param StoreCardRequest $request
     * @param Column $column
     * @return JsonResponse
     */
    public function store(StoreCardRequest $request, Column $column)
    {
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
     * @param Card $card
     * @return JsonResponse
     */
    public function show(ShowCardRequest $request, Card $card)
    {
        return $this->successResponse(CardResource::make($card), 'Card details', 200);
    }

    /**
     * @param UpdateCardRequest $request
     * @param Card $card
     * @return JsonResponse
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        try {
            $card->update($request->validated());
        } catch (\Exception $exception) {
            return $this->errorResponse('Card could not be updated!', 500);
        }
        return $this->successResponse(CardResource::make($card), 'Card updated successfully', 200);
    }

    /**
     * @param DestroyCardRequest $request
     * @param Card $card
     * @return JsonResponse
     */
    public function destroy(DestroyCardRequest $request, Card $card)
    {
        try {
            $card->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Card could not be deleted!', 500);
        }
        return $this->successResponse(null, 'Card deleted successfully', 200);
    }
}
