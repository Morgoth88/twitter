<?php

namespace App\Services;

use App\Exceptions\UserRoleException;

class AdminCheckerService
{

    /**
     * check if user role is 1(admin)
     *
     * @param $user
     * @return bool
     * @throws UserRoleException
     */
    public function isAdmin($user)
    {
        if ($user->role_id === 1) {
            return true;
        } else {
            throw new UserRoleException('Unauthorized action');
        }
    }


    /**
     * check if user role is 0(user)
     *
     * @param $user
     * @return bool
     * @throws UserRoleException
     */
    public function isUser($user)
    {
        if ($user->role_id === 0) {
            return true;
        } else {
            throw new UserRoleException('Unauthorized action');
        }
    }

}
