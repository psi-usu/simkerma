<?php

namespace App\GlobalClass;
use App\User;

class UserAuth {
    public function isSuperUser(User $user)
    {
        return $user->userAuth()->where('auth_type', 'SU')->exists();
    }

    public function isSuperAdminUnit(User $user)
    {
        return $user->userAuth()->where('auth_type', 'SAU')->exists();
    }

    public function isAdminUnit(User $user)
    {
        return $user->userAuth()->where('auth_type', 'AU')->exists();
    }

    public function isAdminProdi(User $user)
    {
        return $user->userAuth()->where('auth_type', 'AP')->exists();
    }
}