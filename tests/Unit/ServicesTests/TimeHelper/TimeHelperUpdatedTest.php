<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeHelperService;
use InvalidArgumentException;

class TimeHelperUpdatedTest extends TestCase
{

    private $timeHelper;
    private $postMock;


    protected function setUp()
    {
        $this->timeHelper = new TimeHelperService();
        $this->postMock = $this->getMockBuilder('Message')->getMock();
    }


    private function timeToDate($time)
    {
        return date('Y-m-d H:i:s', $time);
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

}
