<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Column\IndexColumnRequest;
use App\Http\Requests\Column\ShowColumnRequest;
use App\Http\Requests\Column\StoreColumnRequest;
use App\Http\Requests\Column\UpdateColumnRequest;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * @param IndexColumnRequest $request
     * @param Board $board
     * @return JsonResponse
     */
    public function index(IndexColumnRequest $request, Board $board)
    {
        return $this->successResponse($board->columns, 'Board columns', 200);
    }

    /**
     * @param StoreColumnRequest $request
     * @param Board $board
     * @return JsonResponse
     */
    public function store(StoreColumnRequest $request, Board $board)
    {
        try {
            $column = $board->columns()->create([
                'name' => $request->name,
            ]);
        } catch (\Exception $exception) {
            return $this->errorResponse('Column could not be created!' , 500);
        }
        return $this->successResponse($column, 'Column created successfully', 201);
    }

    /**
     * @param ShowColumnRequest $request
     * @param Column $column
     * @return JsonResponse
     */
    public function show(ShowColumnRequest $request, Column $column)
    {
        return $this->successResponse($column, 'Column Detail', 200);
    }

    /**
     * @param UpdateColumnRequest $request
     * @param Column $column
     * @return JsonResponse
     */
    public function update(UpdateColumnRequest $request, Column $column)
    {
        try {
            $column->update($request->validated());
        } catch (\Exception $exception) {
            return $this->errorResponse('Column could not updated!', 500);
        }
        return $this->successResponse($column, 'Column updated successfully', 200);
    }

    /**
     * @param Column $column
     * @return JsonResponse
     */
    public function destroy(Column $column)
    {
        try {
            $column->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Column could not be deleted!', 500);
        }
        return $this->successResponse(null, 'Column deleted successfully', 200);
    }
}
