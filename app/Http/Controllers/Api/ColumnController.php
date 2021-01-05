<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Column\DestroyColumnRequest;
use App\Http\Requests\Column\StoreColumnRequest;
use App\Http\Requests\Column\UpdateColumnRequest;
use App\Http\Resources\Column\ColumnResource;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ColumnController extends Controller
{
    /**
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Board $board): JsonResponse
    {
        $this->authorize('view', $board);
        $board->columns->load('cards');
        return $this->successResponse(ColumnResource::collection($board->columns), 'Board columns', 200);
    }

    /**
     * @param StoreColumnRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreColumnRequest $request, Board $board): JsonResponse
    {
        $this->authorize('create', $board);
        try {
            $column = $board->columns()->create([
                'name' => $request->name,
            ]);
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Column could not be created!' , 500);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($column)
            ->withProperties(['board_id' => $board->id])
            ->log('create new column');

        return $this->successResponse(ColumnResource::make($column), 'Column created successfully', 201);
    }

    /**
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Board $board, Column $column): JsonResponse
    {
        $this->authorize('view', $board);
        $column->load('cards');
        return $this->successResponse(ColumnResource::make($column), 'Column Detail', 200);
    }

    /**
     * @param UpdateColumnRequest $request
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateColumnRequest $request, Board $board, Column $column): JsonResponse
    {
        $this->authorize('update', $board);
        try {
            $column->update($request->validated());
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Column could not updated!', 500);
        }
        return $this->successResponse(ColumnResource::make($column), 'Column updated successfully', 200);
    }

    /**
     * @param DestroyColumnRequest $request
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyColumnRequest $request, Board $board, Column $column): JsonResponse
    {
        $this->authorize('delete', $board);
        try {
            $column->delete();
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Column could not be deleted!', 500);
        }
        return $this->successResponse(null, 'Column deleted successfully', 200);
    }
}
