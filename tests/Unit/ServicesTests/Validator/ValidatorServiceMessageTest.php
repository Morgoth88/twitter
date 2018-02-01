<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ValidatorService;
use stdClass;
use TypeError;

class ValidatorServiceMessageTest extends TestCase
{

    private $validatorService;


    protected function setUp()
    {
        parent::setUp();
        $this->validatorService = new ValidatorService();
    }


    //data providers
    /**************************************************************************/

    /**
     * valid inputs
     *
     * @return array
     */
    public function providerValidateMessageValidInputs()
    {
        return [
            [
                null, ['tweet' => 'something']
            ],
            [
                null, ['tweet' => 'null']
            ],
        ];
    }


    /**
     * invalid inputs
     *
     * @return array
     */
    public function providerValidateMessageInvalidInputs()
    {
        return [
            [
                ['ghgh'],
                [55665]
            ],
            [
                ['something' => 'something']
            ],
            [
                [127 => 'something']
            ],
            [
                ['tweet' => '']
            ],
            [
                ['tweet' => 123456]
            ]
        ];
    }


    /**
     * error inputs
     *
     * @return array
     */
    public function providerValidateMessageErrorTypeInputs()
    {
        return [
            [''],
            [null],
            [false],
            [true],
            [new stdClass()],
            ['something' => 'something'],
        ];
    }


    //tests
    /**************************************************************************/

    /**
     * test valid inputs
     *
     * @dataProvider providerValidateMessageValidInputs
     * @param $result
     * @param $inputData
     */
    public function testValidateMessageValidInputs($result, $inputData)
    {
        $this->assertEquals($result, $this->validatorService
            ->validateMessage($inputData));
    }


    /**
     * test invalid input data
     *
     * @dataProvider providerValidateMessageInvalidInputs
     * @expectedException Illuminate\Validation\ValidationException
     * @param $inputData
     */
    public function testValidateMessageInvalidInputs($inputData)
    {
        $this->validatorService->validateMessage($inputData);
    }


    /**
     * test error input data
     *
     * @dataProvider providerValidateMessageErrorTypeInputs
     * @expectedException TypeError
     * @param $inputData
     */
    public function testValidateMessageErrorTypeInputs($inputData)
    {
        $this->validatorService->validateMessage($inputData);
    }

}
