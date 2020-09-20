<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Board\DestroyBoardRequest;
use App\Http\Requests\Board\ShowBoardRequest;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Models\Board;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
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
    public function store(StoreBoardRequest $request)
    {
        $board = Board::create([
            'name' => $request->name
        ]);
        $board->users()->attach(auth()->user());

        return $this->successResponse(BoardResource::make($board), 'Board created successfully', 200);
    }

    /**
     * @param ShowBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(ShowBoardRequest $request, Board $board)
    {
        $this->authorize('view', $board);
        return $this->successResponse(BoardResource::make($board), 'Board Details', 200);
    }

    /**
     * @param UpdateBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        try {
            $board->update($request->validated());
            $board->users()->sync($request->user_ids);
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not updated!', 500);
        }
        return $this->successResponse(BoardResource::make($board), 'Board updated successfully', 200);
    }

    /**
     * @param DestroyBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     */
    public function destroy(DestroyBoardRequest $request, Board $board)
    {
        try {
            $board->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not deleted!', 500);
        }
        return $this->successResponse(null, 'Board deleted', 200);
    }
}
