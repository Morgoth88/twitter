<?php

namespace App\services;

use Illuminate\Support\Facades\Hash;

class PasswordCheckerService
{

    /**
     * check accordance user password and request user password
     *
     * @param $request
     * @param $user
     * @return mixed
     */
    public function checkPasswords($request, $user)
    {
        return Hash::check($request->password, $user->password);
    }
}