<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamBoardPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Team $team
     * @return Response
     */
    public function view(User $user, Team $team)
    {
        return $team->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Team $team
     * @param Board $board
     * @return Response
     */
    public function viewAdvanced(User $user, Team $team, Board $board)
    {
        $is_team_user = $team->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        $is_board_user = $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        return ($is_team_user and $is_board_user) ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Team $team
     * @return Response
     */
    public function create(User $user, Team $team)
    {
        return $team->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();

    }

    /**
     * @param User $user
     * @param Team $team
     * @param Board $board
     * @return Response
     */
    public function update(User $user, Team $team, Board $board)
    {
        $is_team_user = $team->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        $is_board_user = $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        return ($is_team_user and $is_board_user) ? Response::allow() : Response::deny();
    }

    /**
     * @param User $user
     * @param Team $team
     * @param Board $board
     * @return Response
     */
    public function delete(User $user, Team $team, Board $board)
    {
        $is_team_user = $team->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        $is_board_user = $board->users()->whereIn('user_id', [$user->id])->exists() ? Response::allow() : Response::deny();
        return ($is_team_user and $is_board_user) ? Response::allow() : Response::deny();
    }
}
