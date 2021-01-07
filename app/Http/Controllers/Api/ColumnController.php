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
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $column = $board->columns()->create([
                'name' => $request->name,
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($board)
                ->log('create new column');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Column could not be created!' , 500);
        }

        DB::commit();

        return $this->successResponse(ColumnResource::make($column), 'Column created successfully', 201);
    }

    /**
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Column $column): JsonResponse
    {
        $this->authorize('view', $column->board);
        $column->load('cards');
        return $this->successResponse(ColumnResource::make($column), 'Column Detail', 200);
    }

    /**
     * @param UpdateColumnRequest $request
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateColumnRequest $request,Column $column): JsonResponse
    {
        $this->authorize('update', $column->board);
        DB::beginTransaction();
        try {
            $column->update($request->validated());

            activity()
                ->causedBy(auth()->user())
                ->performedOn($column->board)
                ->log('update column');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Column could not updated!', 500);
        }

        DB::commit();

        return $this->successResponse(ColumnResource::make($column), 'Column updated successfully', 200);
    }

    /**
     * @param Column $column
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Column $column): JsonResponse
    {
        $this->authorize('delete', $column->board);
        DB::beginTransaction();
        try {
            $column->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($column->board)
                ->log('delete column');
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->errorResponse('Column could not be deleted!', 500);
        }

        DB::commit();

        return $this->successResponse(null, 'Column deleted successfully', 200);
    }
}
