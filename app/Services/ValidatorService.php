<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidatorService
{


    public function validateUserUpdateRequest($DataArray)
    {
            return Validator::make($DataArray, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'new_password' => 'required|string|min:6'
        ])->validate();;
    }


    public function validateUserRegistration($DataArray)
    {
        return Validator::make($DataArray, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    public function validateMessage($DataArray)
    {
        return Validator::make($DataArray, [
            'tweet' => 'required|string'])
            ->validate();
    }


    public function validateComment($DataArray)
    {
        return Validator::make($DataArray, [
            'comment' => 'required|string'])
            ->validate();
    }

}
