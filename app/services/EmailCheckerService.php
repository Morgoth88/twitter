<?php

namespace App\services;

use App\User;

class EmailCheckerService
{

    /**
     *
     * @param $email
     * @return mixed
     */
    public function emailExist($email)
    {
        $result = User::where('email', $email)->first();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
