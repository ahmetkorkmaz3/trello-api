<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Board $board
     * @return Response
     */
    public function view(User $user, Board $board)
    {
        return $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }

    public function create(User $user, Board $board)
    {
        return $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Board $board
     * @return Response
     */
    public function update(User $user, Board $board)
    {
        return $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Board $board
     * @return Response
     */
    public function delete(User $user, Board $board)
    {
        return $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Board $board
     * @return Response
     */
    public function invite(User $user, Board $board)
    {
        return $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }
}
