<?php

namespace App\services;

class BanCheckerService
{

    /**
     * check if user was banned
     *
     * @param $user
     * @return bool
     */
    public function banned($user)
    {
        return $user->ban === 1;
    }

}