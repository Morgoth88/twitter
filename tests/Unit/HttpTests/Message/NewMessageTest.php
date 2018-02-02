<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class NewMessageTest extends TestCase
{

    private $user;
    private $messageText;


    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->messageText = 'comment test';
    }


    protected function tearDown()
    {
        parent::tearDown();
        $this->user->delete();
    }


    /**
     * valid request test
     */
    public function testValidRequest()
    {

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $this->messageText]);

        $response->assertStatus(201)
            ->assertJson([
                'text' => $this->messageText
            ]);
    }


    /**
     * invalid message request test
     */
    public function testInvalidMessageRequest()
    {
        $text = '';

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $text]);

        $response->assertStatus(422);
    }


    /**
     * invalid input name request test
     */
    public function testInvalidInputNameRequest()
    {

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['something'
            => $this->messageText]);

        $response->assertStatus(422);
    }


    /**
     * invalid route test
     */
    public function testInvalidRouteRequest()
    {

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweeteee', ['tweet'
            => $this->messageText]);

        $response->assertStatus(404
        );
    }


    /**
     * unauthorized test
     */
    public function testUnauthorizedRequest()
    {

        $response = $this->json('POST',
            'api/v1/tweet', ['tweet'
            => $this->messageText]);

        $response->assertStatus(401);
    }
}
