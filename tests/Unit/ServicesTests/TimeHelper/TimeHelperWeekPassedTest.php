<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeHelperService;
use InvalidArgumentException;

class TimeHelperWeekPassedTest extends TestCase
{

    private $timeHelper;


    protected function setUp()
    {
        parent::setUp();
        $this->timeHelper = new TimeHelperService();
    }


    private function timeToDate($time)
    {
        return date('Y-m-d H:i:s', $time);
    }


    //true
    /**************************************************************************/
    public function testWeekPassed()
    {
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 7)));

    }


    public function testEightDaysPassed()
    {
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 8)));

    }


    public function testYearPassed()
    {
        $this->assertTrue($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 365)));

    }

    //false
    /**************************************************************************/
    public function testSixDaysPassed()
    {
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 3600 * 24 * 6)));

    }


    public function testMinutePassed()
    {
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() - 60)));

    }


    public function testTommorow()
    {
        $this->assertFalse($this->timeHelper
            ->weekPassed($this->timeToDate(time() + 3600 * 24)));

    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testNull()
    {
        $this->timeHelper->weekPassed(null);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testFalse()
    {
        $this->timeHelper->weekPassed(false);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmpty()
    {
        $this->timeHelper->weekPassed('');
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testTrue()
    {
        $this->timeHelper->weekPassed(true);
    }
}
