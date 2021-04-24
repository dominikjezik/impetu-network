<?php

namespace App\Policies;

use App\Models\SubPage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubPagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the SubPage.
     *
     * @param User $user
     * @param SubPage $subPage
     * @return mixed
     */
    public function update(User $user, SubPage $subPage)
    {
        return $subPage->isAtLeast('admin', $user);
    }

    /**
     * Determine whether the user can delete the SubPage.
     *
     * @param User $user
     * @param SubPage $subPage
     * @return bool
     */
    public function delete(User $user, SubPage $subPage): bool
    {
        return $subPage->isOwner($user);
    }

    /**
     * Determine whether the user can change current owner of the SubPage.
     *
     * @param User $user
     * @param SubPage $subPage
     * @return bool
     */
    public function changeOwner(User $user, SubPage $subPage): bool
    {
        return $subPage->isOwner($user);
    }

    public function manage(User $user, SubPage $subPage): bool
    {
        return $subPage->isAtLeast('moderator', $user);
    }


}
