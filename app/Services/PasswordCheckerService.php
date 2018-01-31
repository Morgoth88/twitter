<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class PasswordCheckerService
{

    /**
     * check accordance user password and request user password
     *
     * @param $requestPassword
     * @param $userPassword
     * @return mixed
     */
    public function checkPasswords($requestPassword, $userPassword)
    {
        return Hash::check($requestPassword, $userPassword);
    }
}