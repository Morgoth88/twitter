<?php

namespace App\services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidatorService
{

    private $emailChecker;


    public function __construct(EmailCheckerService $emailChecker)
    {
        $this->emailChecker = $emailChecker;
    }


    public function validateUserUpdateRequest($request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'new_password' => 'required|string|min:6'
        ])->validate();
    }


    public function ValidateUserRegistration($data)
    {
        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ])->validate();
    }


    /**
     * validate message
     *
     * @param $request
     * @return $this|bool
     */
    public function ValidateMessage($request)
    {
        Validator::make($request->all(), [
            'tweet' => 'required|string'])
            ->validate();
    }


    /**
     * validate comment
     *
     * @param $request
     * @return $this|bool
     */
    public function ValidateComment($request)
    {
        Validator::make($request->all(), [
            'comment' => 'required|string'])
            ->validate();
    }

}
