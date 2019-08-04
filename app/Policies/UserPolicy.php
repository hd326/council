<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, User $signedInUser)
    {
        //return $signedInUser->owns($user); for other cases
        //return $user->owns($post)
        return $signedInUser->id === $user->id;
    }
}
