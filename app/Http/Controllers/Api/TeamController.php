<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\DestroyTeamRequest;
use App\Http\Requests\Team\IndexTeamRequest;
use App\Http\Requests\Team\ShowTeamRequest;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\Team\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    /**
     * @param IndexTeamRequest $request
     * @return JsonResponse
     */
    public function index(IndexTeamRequest $request)
    {
        return $this->successResponse(TeamResource::collection(auth()->user()->teams), 'User teams', 200);
    }

    /**
     * @param StoreTeamRequest $request
     * @return JsonResponse
     */
    public function store(StoreTeamRequest $request)
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
     * @param ShowTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function show(ShowTeamRequest $request, Team $team)
    {
        return $this->successResponse(TeamResource::make($team), 'Team detail', 200);
    }

    /**
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
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
     */
    public function destroy(DestroyTeamRequest $request, Team $team)
    {
        try {
            $team->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Team could not deleted!', 500);
        }
        return $this->successResponse(null, 'Team deleted successfully', 200);
    }
}
