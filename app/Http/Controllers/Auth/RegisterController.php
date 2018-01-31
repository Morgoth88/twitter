<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserDataRepository;
use App\Services\ValidatorService;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Services\LogService;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'api/v1/home';


    private $logService;


    private $userRepo;


    private $validatorService;


    /**
     * RegisterController constructor.
     * @param LogService $logService
     * @param UserDataRepository $userRepo
     * @param ValidatorService $validatorService
     */
    public function __construct(LogService $logService,
                                UserDataRepository $userRepo,
                                ValidatorService $validatorService)
    {
        $this->middleware('guest');
        $this->logService = $logService;
        $this->userRepo = $userRepo;
        $this->validatorService = $validatorService;
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return $this->validatorService->validateUserRegistration($data);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return $this->userRepo->createUser($data);
    }


    /**
     * log registration of user
     *
     * @param Request $request
     * @param $user
     */
    public function registered(Request $request, $user)
    {
        $this->logService->log($user, 'User registered');
    }

}
