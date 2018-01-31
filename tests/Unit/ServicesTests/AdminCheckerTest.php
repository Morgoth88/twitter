<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AdminCheckerService;

class AdminCheckerTest extends TestCase
{

    private $adminChecker;

    private $user;


    protected function setUp()
    {
        $this->adminChecker = new AdminCheckerService();
        $this->user = $this->getMockBuilder('User')->getMock();
    }



    //true
    /**************************************************************************/
    public function testAdminRole()
    {
        $this->user->role_id = 1;
        $this->assertTrue($this->adminChecker->isAdmin($this->user));

    }




    //false
    /**************************************************************************/
    public function testUserRole()
    {
        $this->user->role_id = 0;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testInvalidInt()
    {
        $this->user->role_id = -1;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testString()
    {
        $this->user->role_id = '1';
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testInvalidBigInt()
    {
        $this->user->role_id = 100018;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testNull()
    {
        $this->user->role_id = null;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testEmptyString()
    {
        $this->user->role_id = '';
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testFalse()
    {
        $this->user->role_id = false;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testTrue()
    {
        $this->user->role_id = true;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testArray()
    {
        $this->user->role_id = [1];
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }


    public function testObject()
    {
        $this->user->role_id = $this->user;
        $this->assertFalse($this->adminChecker->isAdmin($this->user));
    }

}
