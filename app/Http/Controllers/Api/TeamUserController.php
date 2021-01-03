<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\User\StoreTeamUserRequest;
use App\Http\Resources\Team\TeamUserResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\TeamUserInvite;
use App\Models\User;
use App\Notifications\InviteNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamUserController extends Controller
{
    /**
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Team $team): JsonResponse
    {
        $this->authorize('view', $team);
        return $this->successResponse(TeamUserResource::collection($team->users), 'Team users', 200);
    }

    /**
     * @param StoreTeamUserRequest $request
     * @param Team $team
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreTeamUserRequest $request, Team $team): JsonResponse
    {
        $this->authorize('create', $team);
        $user = User::where('email', $request->email)->first();

        DB::beginTransaction();
        try {
            if (!$user) {
                $invite = Invite::create([
                    'email' => $request->email,
                    'type' => Invite::TYPE_TEAM,
                    'type_id' => $team->id,
                ]);

                $invite->notify(new InviteNotification());
            }

            $teamUserInvite = $team->teamUserInvites()->create([
                'email' => $request->email,
                'user_id' => $user ? $user->id : null
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            abort(500, 'Server error');
        }
        DB::commit();

        return $this->successResponse($teamUserInvite, 'Success', 201);
    }

    /**
     * @param Team $team
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Team $team, User $user): JsonResponse
    {
        $this->authorize('delete', $team);
        try {
            $team->users()->detach($user->id);
        } catch (\Exception $exception) {
            report($exception);
            abort(500, 'Can not remove user from team');
        }
        return $this->successResponse(null, 'Success', 204);
    }
}
