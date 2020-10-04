<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Column\DestroyColumnRequest;
use App\Http\Requests\Column\ShowColumnRequest;
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
    public function index(Board $board)
    {
        $this->authorize('view', $board);
        return $this->successResponse(ColumnResource::collection($board->columns), 'Board columns', 200);
    }

    /**
     * @param StoreColumnRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreColumnRequest $request, Board $board)
    {
        $this->authorize('create', $board);
        try {
            $column = $board->columns()->create([
                'name' => $request->name,
            ]);
        } catch (\Exception $exception) {
            return $this->errorResponse('Column could not be created!' , 500);
        }
        return $this->successResponse(ColumnResource::collection($column), 'Column created successfully', 201);
    }

    /**
     * @param ShowColumnRequest $request
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(ShowColumnRequest $request, Board $board, Column $column)
    {
        $this->authorize('show', $board);
        return $this->successResponse(ColumnResource::make($column), 'Column Detail', 200);
    }

    /**
     * @param UpdateColumnRequest $request
     * @param Board $board
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateColumnRequest $request, Board $board, Column $column)
    {
        $this->authorize('update', $board);
        try {
            $column->update($request->validated());
        } catch (\Exception $exception) {
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
    public function destroy(DestroyColumnRequest $request, Board $board, Column $column)
    {
        $this->authorize('delete', $board);
        try {
            $column->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Column could not be deleted!', 500);
        }
        return $this->successResponse(null, 'Column deleted successfully', 200);
    }
}
