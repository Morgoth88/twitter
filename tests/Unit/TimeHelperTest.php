<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\services\TimeHelperService;
use InvalidArgumentException;

class TimeHelperTest extends TestCase
{

    private $timeHelper;
    private $postMock;


    protected function setUp()
    {
        $this->timeHelper = new TimeHelperService();
        $this->postMock = $this->getMockBuilder('Message')->getMock();
    }


    public function testUpdated()
    {
        $this->postMock->created_at = $this->timeToDate(time());
        $this->postMock->updated_at = $this->timeToDate(time());

        $this->assertFalse($this->timeHelper->updated($this->postMock));



        $this->postMock->created_at = $this->timeToDate(time() - 60);
        $this->postMock->updated_at = $this->timeToDate(time());

        $this->assertTrue($this->timeHelper->updated($this->postMock));
    }


    private function timeToDate($time)
    {
        return date('Y-m-d H:i:s', $time);
    }


    public function testTwoMinutesHelper()
    {
        //test 2 minutes 1 second
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 121)));

        //test year
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 3600 * 24 * 365)));

        //test tomorrow
        $this->assertFalse($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() + 3600 * 24)));

        //test 1 minute 59 seconds
        $this->assertTrue($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 119)));

        //test 2 minutes
        $this->assertTrue($this->timeHelper
            ->lessThanTwoMinutes($this->timeToDate(time() - 120)));
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testTwoMinutesHelperNull()
    {
        $this->timeHelper->lessThanTwoMinutes(null);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testTwoMinutesHelperFalse()
    {
        $this->timeHelper->lessThanTwoMinutes(false);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testTwoMinutesHelperEmpty()
    {
        $this->timeHelper->lessThanTwoMinutes('');
    }


    public function testWeekPassed()
    {
        //test day ago
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24)));

        //test 6 days ago
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 6)));

        //test tomorrow
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() + 3600 * 24)));

        //test week ago
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 7)));

        //test 8 days ago
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 8)));

        //test year ago
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 365)));
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testWeekPassedNull()
    {
        $this->timeHelper->weekPassed(null);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testWeekPassedFalse()
    {
        $this->timeHelper->weekPassed(false);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testWeekPassedEmpty()
    {
        $this->timeHelper->weekPassed('');
    }

}
