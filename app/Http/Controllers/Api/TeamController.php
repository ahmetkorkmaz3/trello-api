<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\DestroyTeamRequest;
use App\Http\Requests\Team\ShowTeamRequest;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\Team\TeamResource;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(TeamResource::collection(auth()->user()->teams), 'User teams', 200);
    }

    /**
     * @param StoreTeamRequest $request
     * @return JsonResponse
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        try {
            $team = Team::create($request->validated());
            $team->users()->attach(auth()->user());
            if ($request->has('user_ids')) {
                $team->users()->sync($request->user_ids);
            }
        } catch (\Exception $exception) {
            return $this->errorResponse('Team could not be created', 500);
        }
        return $this->successResponse(TeamResource::make($team), 'Team successfully created', 201);
    }

    /**
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Team $team): JsonResponse
    {
        $this->authorize('show', $team);
        return $this->successResponse(TeamResource::make($team), 'Team detail', 200);
    }

    /**
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        $this->authorize('update', $team);
        try {
            $team->update($request->validated());
            if ($request->has('user_ids')) {
                $team->users()->sync($request->user_ids);
            }
        } catch (\Exception $exception) {
            return $this->errorResponse('Team could not updated!', 500);
        }
        return $this->successResponse(TeamResource::make($team), 'Team updated successfully', 200);
    }

    /**
     * @param DestroyTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyTeamRequest $request, Team $team): JsonResponse
    {
        $this->authorize('delete', $team);
        try {
            $team->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Team could not deleted!', 500);
        }
        return $this->successResponse(null, 'Team deleted successfully', 200);
    }
}
