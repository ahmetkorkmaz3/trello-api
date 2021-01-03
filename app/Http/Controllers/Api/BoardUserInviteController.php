<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoarUserInvite\BoardUserInviteResource;
use App\Models\BoardUserInvite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BoardUserInviteController extends Controller
{
    /**,
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        auth()->user()->boardInvites->load('board');
        return $this->successResponse(BoardUserInviteResource::collection(auth()->user()->boardInvites), 'All board invites');
    }

    /**
     * @param BoardUserInvite $boardUserInvite
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(BoardUserInvite $boardUserInvite): JsonResponse
    {
        $this->authorize('update', $boardUserInvite);
        DB::beginTransaction();
        try {
            $boardUserInvite->update(['status' => BoardUserInvite::STATUS_COMPLETED]);
            $boardUserInvite->board->users()->syncWithoutDetaching(auth()->user()->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            abort(500, 'Server error');
        }
        DB::commit();

        return $this->successResponse($boardUserInvite, 'Success');
    }

    /**
     * @param BoardUserInvite $boardUserInvite
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(BoardUserInvite $boardUserInvite): JsonResponse
    {
        $this->authorize('delete', $boardUserInvite);
        DB::beginTransaction();
        try {
            $boardUserInvite->update(['status' => BoardUserInvite::STATUS_REJECTED]);
            $boardUserInvite->delete();
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            abort(500, 'Server error');
        }
        DB::commit();

        return $this->successResponse(null, 'Success', 204);
    }
}
