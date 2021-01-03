<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamUserInvite\TeamUserInviteResource;
use App\Models\TeamUserInvite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TeamUserInviteController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        auth()->user()->teamInvites->load('team');
        return $this->successResponse(TeamUserInviteResource::collection(auth()->user()->teamInvites), 'All team invites');
    }

    /**
     * @param TeamUserInvite $teamUserInvite
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(TeamUserInvite $teamUserInvite): JsonResponse
    {
        $this->authorize('update', $teamUserInvite);
        DB::beginTransaction();
        try {
            $teamUserInvite->update(['status' => TeamUserInvite::STATUS_COMPLETED]);
            $teamUserInvite->team->users()->syncWithoutDetaching(auth()->user()->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->errorResponse('Somethings wrong', 500);
        }
        DB::commit();

        return $this->successResponse($teamUserInvite, 'Success');
    }

    /**
     * @param TeamUserInvite $teamUserInvite
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(TeamUserInvite $teamUserInvite): JsonResponse
    {
        $this->authorize('delete', $teamUserInvite);
        DB::beginTransaction();
        try {
            $teamUserInvite->update(['status' => TeamUserInvite::STATUS_REJECTED]);
            $teamUserInvite->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Invite could not be cancel', 500);
        }
        DB::commit();

        return $this->successResponse(null, 'Success', 204);
    }
}
