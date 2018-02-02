<?php

namespace App\Services;

use App\Rules\OldPasswordMatch;
use App\Rules\UserEmailExist;
use Illuminate\Support\Facades\Validator;

class ValidatorService
{


    public function validateUserUpdateRequest($dataArray, $user)
    {
        return Validator::make($dataArray, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', new
            UserEmailExist($user)],
            'password' => ['required', 'string', 'min:6', new
            OldPasswordMatch($user)],
            'new_password' => 'required|string|min:6'
            ])->validate();;
    }


    public function validateUserRegistration($dataArray)
    {
        return Validator::make($dataArray, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    public function validateMessage($dataArray)
    {
        return Validator::make($dataArray, [
            'tweet' => 'required|string'])
            ->validate();
    }


    public function validateComment($dataArray)
    {
        return Validator::make($dataArray, [
            'comment' => 'required|string'])
            ->validate();
    }

}
