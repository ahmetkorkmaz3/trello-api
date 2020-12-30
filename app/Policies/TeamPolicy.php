<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function view(User $user, Team $team): bool
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function create(User $user, Team $team): bool
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function update(User $user, Team $team): bool
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function delete(User $user, Team $team): bool
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }
}
