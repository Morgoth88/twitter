<?php

namespace Tests\Unit\ServicesTests\Validator\Providers;

use Illuminate\Support\Facades\Hash;
use App\User;

class InvalidInputsDataProvider
{

    private $user;
    private $plainPassword;


    public function __construct()
    {

        $this->plainPassword = '123456';

        $this->user = factory(User::class)->make([
            'password' => Hash::make($this->plainPassword)
        ]);
    }


    public function getData()
    {
        $this->plainPassword = '123456';

        $this->user = factory(User::class)->make([
            'password' => Hash::make($this->plainPassword)
        ]);
        return [
            [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => str_random(6),
                'new_password' => $this->plainPassword,
                ],
        ];
    }

}