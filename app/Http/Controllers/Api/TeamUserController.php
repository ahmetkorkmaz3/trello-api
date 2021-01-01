<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\User\StoreTeamUserRequest;
use App\Http\Resources\Team\TeamUserResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\TeamUserInvite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamUserController extends Controller
{
    /**
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team): JsonResponse
    {
        return $this->successResponse(TeamUserResource::collection($team->users), 'Team users', 200);
    }

    /**
     * @param StoreTeamUserRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function store(StoreTeamUserRequest $request, Team $team): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        DB::beginTransaction();
        try {
            if (!$user) {
                Invite::create([
                    'email' => $request->email,
                    'type' => Invite::TYPE_TEAM,
                    'type_id' => $team->id,
                ]);
            }

            $teamUserInvite = $team->teamUserInvites()->create([
                'email' => $request->email,
                'user_id' => $user ? $user->id : null
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500, 'Server error');
        }
        DB::commit();

        return $this->successResponse($teamUserInvite, 'Success', 201);
    }

    /**
     * @param Team $team
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(Team $team, User $user): JsonResponse
    {
        try {
            $team->users()->detach($user->id);
        } catch (\Exception $exception) {
            abort(500, 'Can not remove user from team');
        }
        return $this->successResponse(null, 'Success', 204);
    }
}
