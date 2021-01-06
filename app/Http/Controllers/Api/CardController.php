<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\DestroyCardRequest;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Http\Resources\Card\CardResource;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Board $board, Column $column): JsonResponse
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
    public function store(StoreCardRequest $request, Board $board, Column $column): JsonResponse
    {
        $this->authorize('create', $board);

        DB::beginTransaction();

        try {
            $card = $column->cards()->create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($board)
                ->log('create new card');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Card could not be created', 500);
        }
        DB::commit();

        return $this->successResponse(CardResource::make($card), 'Card created successfully', 201);

    }

    /**
     * @param Board $board
     * @param Column $column
     * @param Card $card
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Board $board, Column $column, Card $card): JsonResponse
    {
        $this->authorize('view', $board);
        $card->load('checkLists', 'assignedUsers');
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
    public function update(UpdateCardRequest $request, Board $board, Column $column, Card $card): JsonResponse
    {
        $this->authorize('update', $board);

        DB::beginTransaction();

        try {
            $card->update($request->validated());
            activity()
                ->causedBy(auth()->user())
                ->performedOn($board)
                ->log('update card');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Card could not be updated!', 500);
        }

        DB::commit();

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
    public function destroy(DestroyCardRequest $request, Board $board, Column $column, Card $card): JsonResponse
    {
        $this->authorize('delete', $board);

        DB::beginTransaction();

        try {
            $card->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($board)
                ->log('delete card');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Card could not be deleted!', 500);
        }

        DB::commit();

        return $this->successResponse(null, 'Card deleted successfully', 200);
    }
}
