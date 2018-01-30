<?php

namespace App\services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\ValidatorException;

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


    public function ValidateUserRegistration($DataArray)
    {
        return Validator::make($DataArray, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    public function ValidateMessage($DataArray)
    {
        return Validator::make($DataArray, [
            'tweet' => 'required|string'])
            ->validate();
    }


    public function ValidateComment($DataArray)
    {
        return Validator::make($DataArray, [
            'comment' => 'required|string'])
            ->validate();
    }

}
