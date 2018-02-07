<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Session;

class DeleteMessageTest extends TestCase
{

    private $user;
    private $text;
    private $tweet;

    protected function setUp()
    {
        parent::setUp();

        Session::start();

        $this->user = factory(User::class)->create();

        $this->tweet = $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['tweet'
            => 'Test Message']);
    }


    protected function tearDown()
    {
        parent::tearDown();
        $this->user->delete();
    }


    /**
     * valid delete request test
     */
    public function testValidDeleteRequest()
    {

        $jsonResponse = $this->tweet->json();

        $response = $this->actingAs($this->user)
            ->call('DELETE', 'api/v1/tweet/' . $jsonResponse['id'],
                ['_token' => csrf_token()]);

        $response->assertStatus(200);
    }


    /**
     * invalid tweet id delete request test
     */
    public function testInvalidTweetId()
    {

        $this->actingAs($this->user)->json('POST',
            'api/v1/tweet', ['tweet'
            => $this->text]);

        $response = $this->actingAs($this->user)->json('DELETE',
            'api/v1/tweet/1000000000');

        $response->assertStatus(404);

    }

}
