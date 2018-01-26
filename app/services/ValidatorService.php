<?php

namespace App\services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidatorService
{

    public function validateUserUpdateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            Rule::unique('users')->ignore($request->user()->id),
            'new_password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect(route('accountUpdateForm'))
                ->withErrors($validator)
                ->withInput();
        } else {
            return true;
        }
    }


    public function ValidateUserRegistration($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    /**
     * validate message
     *
     * @param $request
     * @return $this|bool
     */
    public function ValidateMessage($request)
    {
        $validator = Validator::make($request->all(), [
            'tweet' => 'required|string']);

        if ($validator->fails()) {
            return redirect(route('index'))
                ->withErrors($validator)
                ->withInput();
        } else {
            return true;
        }
    }

}
