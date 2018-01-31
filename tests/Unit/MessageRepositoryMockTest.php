<?php

namespace Tests\Unit;

use App\CrudClasses\Message\MessageReader;
use Tests\TestCase;


class MessageRepositoryMockTest extends TestCase
{

    private $mockMessageRepo;

    private $messageReader;

    protected function setUp()
    {
        parent::setUp();

        $this->mockMessageRepo = $this->getMockBuilder('App\Repositories\MessageDataRepository')
            ->getMock();

        $this->mockMessageRepo->method('getAllMessages')->willReturn('blabla');

        $this->messageReader = new MessageReader($this->mockMessageRepo);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
       $this->assertEquals('blabla',$this->messageReader->readPost());
    }
}
