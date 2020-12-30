<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\DestroyTeamRequest;
use App\Http\Requests\Team\InviteTeamRequest;
use App\Http\Requests\Team\ShowTeamRequest;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\Board\BoardRequestResource;
use App\Http\Resources\Invite\InviteResource;
use App\Http\Resources\Team\TeamRequestResource;
use App\Http\Resources\Team\TeamResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
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
        $this->authorize('view', $team);
        $team->load('boards');
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

    public function invite(InviteTeamRequest $request, Team $team)
    {
        $this->authorize('invite', $team);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $invite = Invite::create([
                'email' => $request->email,
                'type' => Invite::TYPE_TEAM,
                'type_id' => $team->id
            ]);

            $team_request = $team->requests()->create([
                'email' => $request->email,
                'team_id' => $team->id,
                'invite_id' => $invite->id,
            ]);

            $response['invite'] = InviteResource::make($invite);
            $response['team_request'] = TeamRequestResource::make($team_request);

            return $this->successResponse($response, 'Team invite create successfully', 200);
        }

        $team_request = $team->requests()->create([
            'email' => $request->email,
            'team_id' => $team->id,
            'user_id' => $user->id
        ]);

        $team_request = TeamRequestResource::make($team_request);

        return $this->successResponse($team_request, 'Team Request create successfully', 200);
    }
}
