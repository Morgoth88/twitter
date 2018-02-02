<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AdminCheckerService;
use stdClass;

class AdminCheckerTest extends TestCase
{

    private $adminChecker;

    private $user;


    protected function setUp()
    {
        $this->adminChecker = new AdminCheckerService();
        $this->user = $this->getMockBuilder('User')->getMock();
    }


    public function providerAdminRoleInvalidInputs()
    {
        return [
            [false, 0],
            [false, '0'],
            [false, '1'],
            [false, 'gdfhgfgh'],
            [false, ''],
            [false, null],
            [false, false],
            [false, true],
            [false, new  stdClass()],
            [false, [1]],
            [false, 1.1],
        ];
    }

    //test
    /**************************************************************************/

    /**
     * test admin role with valid input
     */
    public function testAdminRoleValidInput()
    {
        $this->user->role_id = 1;
        $this->assertTrue($this->adminChecker->isAdmin($this->user));
    }


    /**
     * test admin role with invalid inputs
     *
     * @dataProvider providerAdminRoleInvalidInputs
     * @expectedException App\Exceptions\UserRoleException
     * @param $result
     * @param $inputData
     */
    public function testAdminRole($result, $inputData)
    {
        $this->user->role_id = $inputData;
        $this->assertEquals($result, $this->adminChecker->isAdmin($this->user));

    }

}
