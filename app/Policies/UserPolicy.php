<?php

namespace App\Policies;

use App\GlobalClass\UserAuth;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends UserAuth {
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, User $user_create)
    {
        
    }
}
