<?php

namespace Tests\Unit;

use App\Services\BanCheckerService;
use Tests\TestCase;
use stdClass;

class BanCheckerTest extends TestCase
{

    private $banChecker;

    private $user;


    protected function setUp()
    {
        $this->banChecker = new BanCheckerService();
        $this->user = $this->getMockBuilder('User')->getMock();
    }


    public function providerBannedUserInputs()
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

    //test
    /**************************************************************************/

    /**
     * test inputs
     *
     * @dataProvider providerBannedUserInputs
     * @param $result
     * @param $inputData
     */
    public function testBannedUser($result, $inputData)
    {
        $this->user->ban = $inputData;
        $this->assertEquals($result, $this->banChecker->banned($this->user));

    }

}
