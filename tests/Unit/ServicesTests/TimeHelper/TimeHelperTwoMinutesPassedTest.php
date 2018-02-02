<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeHelperService;
use InvalidArgumentException;
use stdClass;
use ErrorException;

class TimeHelperTwoMinutesPassedTest extends TestCase
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
    public function providerLessThanTwoMinutesValidInputs()
    {
        return [
            // now
            [true, $this->timeToDate(time())],
            // 1 second ago
            [true, $this->timeToDate(time() - 1)],
            // 61 seconds ago
            [true, $this->timeToDate(time() - 61)],
            // 1 minute 50 seconds ago
            [true, $this->timeToDate(time() - 110)],
        ];
    }


    /**
     * false inputs
     *
     * @return array
     */
    public function providerLessThanTwoMinutesFalseInputs()
    {
        return [
            // 2 minutes 10 second ago
            [$this->timeToDate(time() - 130)],
            // 4 minutes ago
            [$this->timeToDate(time() - 240)],
            // 1 hour ago
            [$this->timeToDate(time() - 3600)],
            // 1 day ago
            [$this->timeToDate(time() - 3600 * 24)],
            // 1 year ago
            [$this->timeToDate(time() - 3600 * 24 * 365)],
            // 10 years ago
            [$this->timeToDate(time() - 3600 * 24 * 365 * 10)],
            //future
            [$this->timeToDate(time() + 10)],
        ];
    }


    /**
     * invalid inputs
     *
     * @return array
     */
    public function providerLessThanTwoMinutesInvalidInputs()
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
    public function providerLessThanTwoMinutesErrorInputs()
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
     * @dataProvider providerLessThanTwoMinutesValidInputs
     * @param $result
     * @param $inputData
     */
    public function testLessThanTwoMinutesValidInputs($result, $inputData)
    {
        $this->assertEquals($result, $this->timeHelper
            ->lessThanTwoMinutes($inputData));
    }


    /**
     * test function with invalid inputs
     *
     * @expectedException InvalidArgumentException
     *
     * @dataProvider providerLessThanTwoMinutesInvalidInputs
     * @param $inputData
     */
    public function testLessThanTwoMinutesInvalidArgumentException($inputData)
    {
        $this->timeHelper->lessThanTwoMinutes($inputData);
    }


    /**
     * test function with error inputs
     *
     * @expectedException ErrorException
     *
     * @dataProvider providerLessThanTwoMinutesErrorInputs
     * @param $inputData
     */
    public function testLessThanTwoMinutesErrorInputs($inputData)
    {
        $this->timeHelper->lessThanTwoMinutes($inputData);
    }


    /**
     * test function with false inputs
     *
     * @expectedException App\Exceptions\TimeExpiredException
     * @dataProvider providerLessThanTwoMinutesFalseInputs
     * @param $inputData
     */
    public function testLessThanTwoMinutesFalseInputs($inputData)
    {
        $this->timeHelper->lessThanTwoMinutes($inputData);
    }

}
