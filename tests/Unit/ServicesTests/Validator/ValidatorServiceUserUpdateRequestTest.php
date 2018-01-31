<?php

namespace Tests\Unit;

use App\Services\ValidatorService;
use Tests\TestCase;

class ValidatorServiceUserUpdateRequestTest extends TestCase
{

    private $validatorService;


    protected function setUp()
    {
        parent::setUp();
        $this->validatorService = new ValidatorService();
    }


    /**
     * valid validation
     */
    public function testValidName()
    {
        $data = [
            'name' => 'Adam',
            'email' => 'Adam@seznam.cz',
            'new_password' => '123456'
        ];

        $this->assertNull($this->validatorService
            ->validateUserUpdateRequest($data));
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidName()
    {
        $data = [
            'name' => '',
            'email' => 'Adam@seznam.cz',
            'new_password' => '123456'
        ];

        $this->validatorService->validateUserUpdateRequest($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidEmail()
    {
        $data = [
            'name' => 'Adam',
            'email' => '@',
            'new_password' => '123456'
        ];

        $this->validatorService->validateUserUpdateRequest($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidPassword()
    {
        $data = [
            'name' => 'Adam',
            'email' => 'Adam@seznam.cz',
            'new_password' => '1234'
        ];

        $this->validatorService->validateUserUpdateRequest($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidLongName()
    {
        $data = [
            'name' => 'Adambdbdfbfdhggbdfbdfbgdgbdfbdbgbdbdbdfbgbdgbduykkuyilyly
            ylyliyliylyllyuilyulfggwrybumoeagbhhhhhhhhguyutyjtjjrtjjyrtyjrjyrjjy
            ytjrjrtjrtjrtyjtjrtyjjrjrjhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjyyyyyyyyy
            llllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll
            jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
            iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',
            'email' => 'Adam@seznam.cz',
            'new_password' => '123456'
        ];

        $this->validatorService->validateUserUpdateRequest($data);
    }

}
