<?php

namespace App\Policies;

use App\Models\TeamUserInvite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamUserInvitePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param TeamUserInvite $teamUserInvite
     * @return bool
     */
    public function update(User $user, TeamUserInvite $teamUserInvite): bool
    {
        return $user->id == $teamUserInvite->user_id;
    }

    /**
     * @param User $user
     * @param TeamUserInvite $teamUserInvite
     * @return bool
     */
    public function delete(User $user, TeamUserInvite $teamUserInvite): bool
    {
        return $user->id == $teamUserInvite->user_id and $teamUserInvite->status == TeamUserInvite::STATUS_PENDING;
    }
}
