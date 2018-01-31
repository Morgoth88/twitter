<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;


class databaseTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }


    /**
     * create record in Db and test his presence
     */
    public function testRecordPresence()
    {
        $user = factory(User::class)->create();
        $text = 'testMessage'.rand(0,10000000);

        $this->actingAs($user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $text]);

        $this->assertDatabaseHas('message', [
            'text' => $text
        ]);
    }

}
