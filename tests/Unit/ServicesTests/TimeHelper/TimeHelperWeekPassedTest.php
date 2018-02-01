<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeHelperService;
use InvalidArgumentException;
use stdClass;
use ErrorException;

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


    //data providers
    /**************************************************************************/

    /**
     * valid inputs
     *
     * @return array
     */
    public function providerWeekPassedValidInputs()
    {
        return [
            // now
            [false, $this->timeToDate(time())],
            // second ago
            [false, $this->timeToDate(time() - 1)],
            // 30 minutes ago
            [false, $this->timeToDate(time() - 1800)],
            // 1 hour ago
            [false, $this->timeToDate(time() - 3600)],
            // day ago
            [false, $this->timeToDate(time() - 3600 * 24)],
            // 3 days ago
            [false, $this->timeToDate(time() - 3600 * 24 * 3)],
            // 6 days ago
            [false, $this->timeToDate(time() - 3600 * 24 * 6)],
            // 6 days 23 minutes 50 seconds ago
            [false, $this->timeToDate(time() - 3600 * 24 * 7 + 10)],
            // future
            [false, $this->timeToDate(time() + 10)],

            // 7 days ago
            [true, $this->timeToDate(time() - 3600 * 26 * 7)],
            // 7 days 10 seconds ago
            [true, $this->timeToDate(time() - 3600 * 24 * 7 - 10)],
            // 8 days ago
            [true, $this->timeToDate(time() - 3600 * 24 * 8)],
            // 1 year ago
            [true, $this->timeToDate(time() - 3600 * 24 * 365)],
            // 10 years ago
            [true, $this->timeToDate(time() - 3600 * 24 * 365 * 10)],
        ];
    }


    /**
     * invalid inputs
     *
     * @return array
     */
    public function providerWeekPassedInvalidInputs()
    {
        return [
            [null],
            [false],
            [true],
            [''],
        ];
    }


    /**
     * error inputs
     *
     * @return array
     */
    public function providerWeekPassedErrorInputs()
    {
        return [
            [[$this->timeToDate(time() - 119)]],
            [new stdClass()],
        ];
    }


    //tests
    /**************************************************************************/

    /**
     * test function with valid inputs
     *
     * @dataProvider providerWeekPassedValidInputs
     * @param $result
     * @param $inputData
     */
    public function testWeekPassedValidInputs($result, $inputData)
    {
        $this->assertEquals($result, $this->timeHelper
            ->weekPassed($inputData));
    }


    /**
     * test function with invalid inputs
     *
     * @expectedException InvalidArgumentException
     * @dataProvider providerWeekPassedInvalidInputs
     * @param $inputData
     */
    public function testWeekPassedInvalidInputs($inputData)
    {
        $this->timeHelper->weekPassed($inputData);
    }


    /**
     * test function with error inputs
     *
     * @expectedException ErrorException
     * @dataProvider providerWeekPassedErrorInputs
     * @param $inputData
     */
    public function testWeekPassedErrorInputs($inputData)
    {
        $this->timeHelper->weekPassed($inputData);
    }

}
