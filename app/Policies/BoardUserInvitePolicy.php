<?php

namespace App\Policies;

use App\Models\BoardUserInvite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardUserInvitePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param BoardUserInvite $boardUserInvite
     * @return bool
     */
    public function update(User $user, BoardUserInvite $boardUserInvite): bool
    {
        return $user->id == $boardUserInvite->user_id;
    }

    /**
     * @param User $user
     * @param BoardUserInvite $boardUserInvite
     * @return bool
     */
    public function delete(User $user, BoardUserInvite $boardUserInvite): bool
    {
        return $user->id == $boardUserInvite->user_id and $boardUserInvite->status == BoardUserInvite::STATUS_PENDING;
    }
}
