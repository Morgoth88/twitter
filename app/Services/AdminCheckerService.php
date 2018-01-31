<?php

namespace App\Services;

class AdminCheckerService
{

    /**
     * check if user role is 1(admin)
     *
     * @param $user
     * @return bool
     */
    public function isAdmin($user)
    {
        return $user->role_id === 1;
    }

}
