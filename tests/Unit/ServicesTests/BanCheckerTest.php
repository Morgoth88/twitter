<?php

namespace Tests\Unit;

use App\Services\BanCheckerService;
use Tests\TestCase;

class BanCheckerTest extends TestCase
{

    private $banChecker;

    private $user;


    protected function setUp()
    {
        $this->banChecker = new BanCheckerService();
        $this->user = $this->getMockBuilder('User')->getMock();
    }




    //true
    /**************************************************************************/
    public function testBannedUser()
    {
        $this->user->ban = 1;
        $this->assertTrue($this->banChecker->banned($this->user));

    }



    //false
    /**************************************************************************/
    public function testNotBannedUser()
    {
        $this->user->ban = 0;
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testInvalidInt()
    {
        $this->user->ban = -1;
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testString()
    {
        $this->user->ban = '1';
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testInvalidBigInt()
    {
        $this->user->ban = 100018;
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testNull()
    {
        $this->user->ban = null;
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testEmptyString()
    {
        $this->user->ban = '';
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testFalse()
    {
        $this->user->ban = false;
        $this->assertFalse($this->banChecker->banned($this->user));
    }

    public function testTrue()
    {
        $this->user->ban = true;
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testArray()
    {
        $this->user->ban = [1];
        $this->assertFalse($this->banChecker->banned($this->user));
    }


    public function testObject()
    {
        $this->user->ban = $this->user;
        $this->assertFalse($this->banChecker->banned($this->user));
    }

}
