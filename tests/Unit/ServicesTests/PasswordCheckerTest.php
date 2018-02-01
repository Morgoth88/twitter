<?php

namespace Tests\Unit;

use App\Services\PasswordCheckerService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use stdClass;

class PasswordCheckerTest extends TestCase
{

    private $passwordChecker;


    protected function setUp()
    {
        parent::setUp();
        $this->passwordChecker = new PasswordCheckerService();
    }


    //data providers
    /**************************************************************************/

    /**
     * valid PasswordCheck inputs provider
     *
     * @return array
     */
    public function providerValidPasswordCheckInputs()
    {
        return [
            'password match' => [true, '123456', '123456'],

            'password not match' => [false, '123456', '123'],
            'password not match' => [false, '654321', '123456'],
            'password not match' => [false, '1234567', '654321'],
            'hashed password is empty' => [false, '123456', ''],
            'plain password is empty' => [false, '', '123456'],
            'plain password is null' => [false, null, '123456'],
            'hashed password is null' => [false, '123456', null],
            'hashed password is true' => [false, '123456', true],
            'plain password is true' => [false, true, '123456'],
        ];
    }

    //tests
    /**************************************************************************/

    /**
     * test password checker with multiple inputs
     *
     * @dataProvider providerValidPasswordCheckInputs
     * @param $plainPassword
     * @param $hashedPassword
     * @param $result
     */
    public function testValidPasswordCheck($result, $plainPassword,
                                           $hashedPassword)
    {
        $this->assertEquals($result, $this->passwordChecker->checkPasswords(
            $plainPassword, Hash::make($hashedPassword)));
    }


}
