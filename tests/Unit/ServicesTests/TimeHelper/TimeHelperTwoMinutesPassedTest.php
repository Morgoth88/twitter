<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeHelperService;
use InvalidArgumentException;

class TimeHelperTwoMinutesPassedTest extends TestCase
{

    private $timeHelper;

    private $postMock;


    protected function setUp()
    {
        parent::setUp();
        $this->timeHelper = new TimeHelperService();
        $this->postMock = $this->getMockBuilder('Message')->getMock();
    }


    private function timeToDate($time)
    {
        return date('Y-m-d H:i:s', $time);
    }


    //true
    /**************************************************************************/
    public function testTwoMinutes()
    {
        $this->assertTrue($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 120)));
    }


    public function testOneMinuteFiftyNineSeconds()
    {
        $this->assertTrue($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 119)));
    }


    //false
    /**************************************************************************/
    public function testTwoMinutesOneSecond()
    {
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 121)));
    }


    public function testYear()
    {
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 3600 * 24 * 365)));
    }


    public function testTomorrow()
    {
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() + 3600 * 24)));
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testNull()
    {
        $this->timeHelper->lessThanTwoMinutes(null);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testFalse()
    {
        $this->timeHelper->lessThanTwoMinutes(false);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmpty()
    {
        $this->timeHelper->lessThanTwoMinutes('');
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testTrue()
    {
        $this->timeHelper->lessThanTwoMinutes(true);
    }


}
