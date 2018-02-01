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


    public function providerAdminRoleInputs()
    {
        return [
            [true, 1],
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
     * test admin role
     *
     * @dataProvider providerAdminRoleInputs
     * @param $result
     * @param $inputData
     */
    public function testAdminRole($result, $inputData)
    {
        $this->user->role_id = $inputData;
        $this->assertEquals($result ,$this->adminChecker->isAdmin($this->user));

    }

}
