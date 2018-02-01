<?php

namespace Tests\Unit\ServicesTests\Validator;

class UserUpdateValidationDataProvider
{

    public function getTestData()
    {
        return [
            [
                ['xdxsx'], ['fdfgd']
            ],
            [
                [
                    str_random(5) => str_random(5),
                    str_random(5) => 'pepa@email.com',
                    str_random(5) => str_random(8),
                ]
            ],
            [
                [
                    str_random(5) => str_random(5),
                    str_random(5) => str_random(5),
                    str_random(5) => str_random(5),
                ]
            ],
            [
                [
                    str_random(5) => str_random(5),
                    'email' => str_random(5),
                    'new_password' => str_random(5),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'email' => str_random(5),
                    str_random(5) => str_random(5),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    str_random(5) => str_random(5),
                    'new_password' => str_random(5),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'email' => str_random(5),
                    'new_password' => str_random(5),
                ]
            ],
            [
                [
                    'name' => str_random(260),
                    'email' => 'pepa@email.com',
                    'new_password' => str_random(6),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'email' => str_random(5),
                    'new_password' => str_random(6),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'email' => 'pepa@email.com',
                    'new_password' => str_random(5),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'email' => 'pepa@email.com',
                ]
            ],
            [
                [
                    'email' => 'pepa@email.com',
                    'new_password' => str_random(6),
                ]
            ],
            [
                [
                    'name' => str_random(5),
                    'new_password' => str_random(6),
                ]
            ],
        ];
    }
}