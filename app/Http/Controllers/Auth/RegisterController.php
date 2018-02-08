<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserDataRepository;
use App\Services\ValidatorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;

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

    private $userRepo;

    private $validatorService;


    /**
     * RegisterController constructor.
     * @param UserDataRepository $userRepo
     * @param ValidatorService $validatorService
     */
    public function __construct(UserDataRepository $userRepo,
                                ValidatorService $validatorService)
    {
        $this->middleware('guest');
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
        Log::notice('User registration', ['id' => $user->id]);
    }
}
