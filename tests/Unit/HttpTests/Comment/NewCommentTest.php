<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class NewCommentTest extends TestCase
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
        $text = 'TestComment';

        $message = $user->message()->create([
            'text' => 'testMessageWithComment'
        ]);

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $text]);

        $response->assertStatus(201);

    }


    /**
     * invalid message request test
     */
    public function testInvalidCommentRequest()
    {
        $user = factory(User::class)->create();
        $text = '';

        $message = $user->message()->create([
            'text' => 'testMessageWithComment'
        ]);

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $text]);

        $response->assertStatus(422);
    }


    /**
     * invalid input name request test
     */
    public function testInvalidInputNameRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestComment';

        $message = $user->message()->create([
            'text' => 'testMessageWithComment'
        ]);

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comm'
            => $text]);

        $response->assertStatus(422);
    }


    /**
     * invalid route test
     */
    public function testInvalidRouteRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestComment';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet/comment', ['comment'
            => $text]);

        $response->assertStatus(405);
    }


    /**
     * invalid message id test
     */
    public function testInvalidMessageIdRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestComment';

        $response = $this->actingAs($user)->json('POST',
            'api/v1/tweet/null/comment', ['comment'
            => $text]);

        $response->assertStatus(404);
    }

    /**
     * unauthorized test
     */
    public function testUnauthorizedRequest()
    {
        $user = factory(User::class)->create();
        $text = 'TestComment';

        $message = $user->message()->create([
            'text' => 'testMessageWithComment'
        ]);

        $response = $this->json('POST',
            'api/v1/tweet/' . $message->id . '/comment', ['comment'
            => $text]);

        $response->assertStatus(401);

    }

}
