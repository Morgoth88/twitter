<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class NewCommentTest extends TestCase
{

    private $user;
    private $messageText;
    private $commentText;


    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->messageText = 'comment test';
        $this->commentText = 'test comment';
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
        $message = $this->user->message()->create([
            'text' => $this->messageText
        ]);

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $this->commentText]);

        $response->assertStatus(201);

        $message->delete();
    }


    /**
     * invalid message request test
     */
    public function testInvalidCommentRequest()
    {
        $text = '';

        $message = $this->user->message()->create([
            'text' => $this->messageText
        ]);

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $text]);

        $response->assertStatus(422);

        $message->delete();
    }


    /**
     * invalid input name request test
     */
    public function testInvalidInputNameRequest()
    {
        $message = $this->user->message()->create([
            'text' => $this->messageText
        ]);

        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comm'
            => $this->commentText]);

        $response->assertStatus(422);

        $message->delete();
    }


    /**
     * invalid route test
     */
    public function testInvalidRouteRequest()
    {
        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet/comment', ['comment'
            => $this->commentText]);

        $response->assertStatus(405);
    }


    /**
     * invalid message id test
     */
    public function testInvalidMessageIdRequest()
    {
        $response = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet/null/comment', ['comment'
            => $this->commentText]);

        $response->assertStatus(404);
    }


    /**
     * unauthorized test
     */
    public function testUnauthorizedRequest()
    {
        $message = $this->user->message()->create([
            'text' => $this->messageText
        ]);

        $response = $this->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $this->commentText]);

        $response->assertStatus(401);

        $message->delete();
    }

}
