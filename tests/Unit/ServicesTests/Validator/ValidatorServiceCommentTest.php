<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ValidatorService;
use stdClass;
use TypeError;

class ValidatorServiceCommentTest extends TestCase
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
    public function providerValidateCommentValidInputs()
    {
        return [
            [
                null, ['comment' => 'something']
            ],
            [
                null, ['comment' => 'null']
            ],
        ];
    }


    /**
     * invalid inputs
     *
     * @return array
     */
    public function providerValidateCommentInvalidInputs()
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
                ['comment' => '']
            ],
            [
                ['comment' => 123456]
            ]
        ];
    }


    /**
     * error inputs
     *
     * @return array
     */
    public function providerValidateCommentErrorTypeInputs()
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
     * @dataProvider providerValidateCommentValidInputs
     * @param $result
     * @param $inputData
     */
    public function testValidateCommentValidInputs($result, $inputData)
    {
        $this->assertEquals($result, $this->validatorService
            ->validateComment($inputData));
    }


    /**
     * test invalid input data
     *
     * @dataProvider providerValidateCommentInvalidInputs
     * @expectedException Illuminate\Validation\ValidationException
     * @param $inputData
     */
    public function testValidateCommentInvalidInputs($inputData)
    {
        $this->validatorService->validateComment($inputData);
    }


    /**
     * test error input data
     *
     * @dataProvider providerValidateCommentErrorTypeInputs
     * @expectedException TypeError
     * @param $inputData
     */
    public function testValidateCommentErrorTypeInputs($inputData)
    {
        $this->validatorService->validateComment($inputData);
    }

}
