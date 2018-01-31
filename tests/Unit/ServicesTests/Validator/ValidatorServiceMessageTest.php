<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ValidatorService;

class ValidatorServiceMessageTest extends TestCase
{

    private $validatorService;


    protected function setUp()
    {
        parent::setUp();
        $this->validatorService = new ValidatorService();
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidEmptyTweet()
    {
        $data = [
            'tweet' => ''
        ];

        $this->validatorService->validateMessage($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidArrayTweet()
    {
        $data = [
            'tweet' => ['dfdsf']
        ];

        $this->validatorService->validateMessage($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidIntTweet()
    {
        $data = [
            'tweet' => 58
        ];

        $this->validatorService->validateMessage($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidObjectTweet()
    {
        $data = [
            'tweet' => new \stdClass()
        ];

        $this->validatorService->validateMessage($data);
    }


    /**
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testInvalidNullTweet()
    {
        $data = [
            'tweet' => null
        ];

        $this->validatorService->validateMessage($data);
    }
}
