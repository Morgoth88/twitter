<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class OldPasswordMatch implements Rule
{

    private $user;


    /**
     * OldPasswordMatch constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    /**
     *  Determine if the validation rule passes.
     *
     * @param mixed $attribute
     * @param $value
     * @return mixed
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, $this->user->password);
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Old password not match!';
    }
}
