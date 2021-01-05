<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Board\DestroyBoardRequest;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Models\Board;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(
            BoardResource::collection(auth()->user()->boards),
    'Auth user boards',
        200
        );
    }

    /**
     * @param StoreBoardRequest $request
     * @return JsonResponse
     */
    public function store(StoreBoardRequest $request): JsonResponse
    {
        $board = Board::create([
            'name' => $request->name
        ]);
        $board->users()->attach(auth()->user());

        return $this->successResponse(BoardResource::make($board), 'Board created successfully', 201);
    }

    /**
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Board $board): JsonResponse
    {
        $this->authorize('view', $board);
        $board->load('columns', 'users');
        return $this->successResponse(BoardResource::make($board), 'Board Details', 200);
    }

    /**
     * @param UpdateBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBoardRequest $request, Board $board): JsonResponse
    {
        $this->authorize('update', $board);
        try {
            $board->update($request->validated());
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Board could not updated!', 500);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($board)
            ->log('update board details');

        return $this->successResponse(BoardResource::make($board), 'Board updated successfully', 200);
    }

    /**
     * @param DestroyBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyBoardRequest $request, Board $board): JsonResponse
    {
        $this->authorize('delete', $board);
        try {
            $board->delete();
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Board could not deleted!', 500);
        }
        return $this->successResponse(null, 'Board deleted', 200);
    }
}
