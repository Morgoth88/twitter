<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class databaseTest extends TestCase
{

    private $user;
    private $text;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::find(2);
        $this->text = 'Database Test'.rand(0,10000000);
    }


    /**
     * create record in Db and test his presence
     */
    public function testRecordPresence()
    {
        $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $this->text]);

        $this->assertDatabaseHas('message', [
            'text' => $this->text
        ]);
    }

}
