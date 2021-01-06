<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardUser\StoreBoardUserRequest;
use App\Http\Resources\Board\BoardUserResource;
use App\Models\Board;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BoardUserController extends Controller
{
    /**
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Board $board): JsonResponse
    {
        $this->authorize('view', $board);
        return $this->successResponse(BoardUserResource::collection($board->users), 'Board users');
    }

    /**
     * @param StoreBoardUserRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBoardUserRequest $request, Board $board): JsonResponse
    {
        $this->authorize('create', $board);
        $user = User::where('email', $request->email)->first();

        DB::beginTransaction();
        try {
            if (!$user) {
                Invite::create([
                    'email' => $request->email,
                    'type' => Invite::TYPE_BOARD,
                    'type_id' => $board->id,
                ]);
            }

            $boardUserInvite = $board->userInvites()->create([
                'email' => $request->email,
                'user_id' => $user ? $user->id : null,
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            abort(500, 'Server error');
        }

        DB::commit();

        return $this->successResponse($boardUserInvite, 'Success', 201);
    }

    /**
     * @param Board $board
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Board $board, User $user): JsonResponse
    {
        $this->authorize('delete', $board);
        try {
            $board->users()->detach($user->id);
        } catch (\Exception $exception) {
            report($exception);
            return $this->errorResponse('Server error', 500);
        }

        return $this->successResponse(null, 'Success', 204);
    }
}
