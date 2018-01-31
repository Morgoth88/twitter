<?php

namespace Tests\Unit;

use App\Services\PasswordCheckerService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordCheckerTest extends TestCase
{

    private $passwordChecker;


    protected function setUp()
    {
        parent::setUp();
        $this->passwordChecker = new PasswordCheckerService();
    }


    public function testValidPasswordCheck()
    {
        $hashedPassword = Hash::make('123456');
        $plainPassword = '123456';

        $this->assertTrue($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }


    public function testSwappedPasswords()
    {
        $hashedPassword = '123456';
        $plainPassword = Hash::make('123456');

        $this->assertFalse($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }


    public function testNotMatchedPasswords()
    {
        $hashedPassword = Hash::make('123456');
        $plainPassword = '654321';

        $this->assertFalse($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }


    public function testNotHashedPassword()
    {
        $hashedPassword = '123456';
        $plainPassword = '123456';

        $this->assertFalse($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }


    public function testEmptyPassword()
    {
        $hashedPassword = '';
        $plainPassword = '';

        $this->assertFalse($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }


    public function testNullPasswords()
    {
        $hashedPassword = null;
        $plainPassword = null;

        $this->assertFalse($this->passwordChecker->checkPasswords(
            $plainPassword, $hashedPassword));
    }

}
