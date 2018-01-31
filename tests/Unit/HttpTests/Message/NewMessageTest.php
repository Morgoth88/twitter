<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class NewMessageTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

    }


    /**
     * valid request test
     */
    public function testValidRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestMessage';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $text]);

        $response->assertStatus(201)
            ->assertJson([
                'text' => $text
            ]);
    }


    /**
     * invalid message request test
     */
    public function testInvalidMessageRequest()
    {
        $user = factory(User::class)->create();
        $text = '';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $text]);

        $response->assertStatus(422);
    }


    /**
     * invalid input name request test
     */
    public function testInvalidInputNameRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestMessage';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet', ['something'
            => $text]);

        $response->assertStatus(422);
    }


    /**
     * invalid route test
     */
    public function testInvalidRouteRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestMessage';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweeteee', ['tweet'
            => $text]);

        $response->assertStatus(404
        );
    }


    /**
     * unauthorized test
     */
    public function testUnauthorizedRequest()
    {
        $text = 'TestMessage';

        $response = $this->json('POST',
            'api/v1/tweet', ['tweet'
            => $text]);

        $response->assertStatus(401);
    }
}
