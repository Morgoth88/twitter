<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Services\ValidatorService;
use Tests\Unit\ServicesTests\Validator\Providers\InvalidInputsDataProvider;

class ValidatorServiceUserUpdateValidationTest extends TestCase
{

    private $validatorService;

   // private $invalidInputsDataProvider;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
      //  $this->invalidInputsDataProvider = new InvalidInputsDataProvider();
    }


    protected function setUp()
    {
        parent::setUp();

        $this->validatorService = new ValidatorService();
    }

/*
    public function providerInvalidInputs()
    {
        return $this->invalidInputsDataProvider->getData();
    }
*/

    /**
     * test valid inputs
     */
    public function testValidInput()
    {
        $plainPassword = '123456';
        $user = factory(User::class)->make([
            'password' => Hash::make($plainPassword)
        ]);

        $dataArray = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $plainPassword,
            'new_password' => $plainPassword,
        ];

        $this->assertNull($this->validatorService
            ->validateUserUpdateRequest($dataArray, $user));

        $dataArray = [
            'name' => str_random(255),
            'email' => 'something.' . $user->email . 'something',
            'password' => $plainPassword,
            'new_password' => $plainPassword . '789',
        ];

        $this->assertNull($this->validatorService
            ->validateUserUpdateRequest($dataArray, $user));
    }


    /**
     * test invalid inputs
     *
     * @expectedException Illuminate\Validation\ValidationException
     * @dataProvider providerInvalidInputs
     * @param $dataArray
     *
    public function testInValidInput($dataArray)
    {
        $plainPassword = '123456';
        $user = factory(User::class)->make([
            'password' => Hash::make($plainPassword)
        ]);

        $this->validatorService
            ->validateUserUpdateRequest($dataArray, $user);
    }
*/
}
