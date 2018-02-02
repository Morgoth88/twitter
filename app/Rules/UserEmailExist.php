<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UserEmailExist implements Rule
{

    private $user;


    /**
     * UserEmailExist constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $userWithCurrentEmail = User::where('email', $value)->first();
        if ($userWithCurrentEmail) {
            return $userWithCurrentEmail->id === $this->user->id;
        } else {
            return true;
        }
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email has already been taken...';
    }
}
