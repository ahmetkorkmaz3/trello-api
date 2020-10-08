<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardUser\StoreBoardUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Board;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;

class BoardUserController extends Controller
{
    public function index(Board $board)
    {
        return UserResource::make($board->users);
    }

    public function store(StoreBoardUserRequest $request, Board $board)
    {
        $user = User::query()->where('email', $request->email)->first();
        $invite = Invite::create([
            'email' => $request->email,
            'type' => Invite::TYPE_BOARD,
            'type_id' => $board->id,
        ]);
        if (!$user) {
            // TODO: send mail
        }

        return response()->json($invite);
    }
}
