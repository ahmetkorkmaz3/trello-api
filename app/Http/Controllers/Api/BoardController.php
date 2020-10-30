<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Board\DestroyBoardRequest;
use App\Http\Requests\Board\InviteBoardRequest;
use App\Http\Requests\Board\ShowBoardRequest;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\Board\BoardRequestResource;
use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Invite\InviteResource;
use App\Models\Board;
use App\Models\BoardRequest;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

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

        return $this->successResponse(BoardResource::make($board), 'Board created successfully', 201);
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
     * @throws AuthorizationException
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        $this->authorize('update', $board);
        try {
            $board->update($request->validated());
            if ($request->has('user_ids')) {
                $board->users()->sync($request->user_ids);
            }
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not updated!', 500);
        }
        return $this->successResponse(BoardResource::make($board), 'Board updated successfully', 200);
    }

    /**
     * @param DestroyBoardRequest $request
     * @param Board $board
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DestroyBoardRequest $request, Board $board)
    {
        $this->authorize('delete', $board);
        try {
            $board->delete();
        } catch (\Exception $exception) {
            return $this->errorResponse('Board could not deleted!', 500);
        }
        return $this->successResponse(null, 'Board deleted', 200);
    }

    public function invite(InviteBoardRequest $request, Board $board)
    {
        $this->authorize('invite', $board);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $invite = Invite::create([
                'email' => $request->email,
                'type' => Invite::TYPE_BOARD,
                'type_id' => $board->id
            ]);

            $board_request = $board->requests()->create([
                'email' => $request->email,
                'board_id' => $board->id,
                'invite_id' => $invite->id,
            ]);

            $response['invite'] = InviteResource::make($invite);
            $response['board_request'] = BoardRequestResource::make($board_request);

            return $this->successResponse($response, 'Board invite create successfully', 200);
        }

        $board_request = $board->requests()->create([
            'email' => $request->email,
            'board_id' => $board->id,
            'user_id' => $user->id,
        ]);

        $board_request = BoardRequestResource::make($board_request);

        return $this->successResponse($board_request, 'Board Request create successfully', 200);
    }
}
