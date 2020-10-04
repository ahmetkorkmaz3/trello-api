<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\Board\StoreBoardRequest;
use App\Http\Requests\Team\Board\UpdateBoardRequest;
use App\Models\Board;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class TeamBoardController extends Controller
{
    /**
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Team $team)
    {
        $this->authorize('view', $team);
        return $this->successResponse($team->boards, 'Auth user team boards', 200);
    }

    /**
     * @param StoreBoardRequest $request
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBoardRequest $request, Team $team)
    {
        $this->authorize('create', $team);
        try {
            $board = $team->boards()->create($request->validated());
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not created', 500);
        }
        return $this->successResponse($board, 'Board created successfully', 201);
    }

    /**
     * @param Team $team
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Team $team, Board $board)
    {
        $this->authorize('viewAdvanced', [$team, $board]);
        return $this->successResponse($board, 'Board detail', 200);
    }

    /**
     * @param UpdateBoardRequest $request
     * @param Team $team
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBoardRequest $request, Team $team, Board $board)
    {
        $this->authorize('update', [$team, $board]);
        try {
            $board->update($request->validated());
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not updated!', 500);
        }
        return $this->successResponse($board, 'Board updated successfully', 200);
    }

    /**
     * @param Team $team
     * @param Board $board
     * @return JsonResponse
     */
    public function destroy(Team $team, Board $board)
    {
        $this->authorize('delete', [$team, $board]);
        try {
            $board->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not deleted!', 500);
        }
        return $this->successResponse(null, 'Board deleted successfully', 200);
    }
}
