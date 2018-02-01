<?php

namespace Tests\Unit;

use App\Services\ValidatorService;
use Tests\TestCase;
use Tests\Unit\ServicesTests\Validator\UserUpdateValidationDataProvider;
use TypeError;
use stdClass;

class ValidatorServiceUserUpdateRequestTest extends TestCase
{

    private $validatorService;

    private $userUpdateValidationDataProvider;


    public  function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->userUpdateValidationDataProvider =
            new UserUpdateValidationDataProvider();
    }


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
    public function providerUserUpdateRequestValidInputs()
    {
        return [
            [
                null, [
                'name' => str_random(5),
                'email' => 'Adam@seznam.cz',
                'new_password' => str_random(6)
            ]
            ],
            [
                null, [
                'name' => str_random(255),
                'email' => 'Ad@sem.com',
                'new_password' => str_random(20)
            ]
            ],
        ];
    }


    /**
     * invalid inputs
     *
     * @return array
     */
    public function providerUserUpdateRequestInvalidInputs()
    {
        return $this->userUpdateValidationDataProvider->getTestData();
    }


    /**
     * error inputs
     *
     * @return array
     */
    public function providerUserUpdateRequestErrorInputs()
    {
        return [
            [''],
            [null],
            [false],
            [true],
            [new stdClass()],
        ];
    }


    //tests
    /**************************************************************************/

    /**
     * test function with valid inputs
     *
     * @dataProvider providerUserUpdateRequestValidInputs
     * @param $result
     * @param $inputData
     */
    public function testValidName($result, $inputData)
    {
        $this->assertEquals($result, $this->validatorService
            ->validateUserUpdateRequest($inputData));
    }


    /**
     * test function with invalid inputs
     *
     * @expectedException Illuminate\Validation\ValidationException
     * @dataProvider providerUserUpdateRequestInvalidInputs
     * @param $inputData
     */
    public function testInvalidName($inputData)
    {
        $this->validatorService->validateUserUpdateRequest($inputData);
    }


    /**
     * test function with error inputs
     *
     * @expectedException TypeError
     * @dataProvider providerUserUpdateRequestErrorInputs
     * @param $inputData
     */
    public function testInvalidEmail($inputData)
    {

        $this->validatorService->validateUserUpdateRequest($inputData);
    }

}
