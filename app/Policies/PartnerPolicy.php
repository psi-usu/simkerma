<?php

namespace App\Policies;

use App\GlobalClass\UserAuth;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy extends UserAuth {
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

    public function create(User $user)
    {
        if ($this->isSuperUser($user) || $this->isAdminUnit($user) || $this->isAdminProdi($user))
            return true;
        else
            return false;
    }
}
