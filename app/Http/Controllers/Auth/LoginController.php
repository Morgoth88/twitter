<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BanCheckerService;
use App\Services\LogService;
use App\Services\RedirectService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    const BAN_MSG = 'Your account was banned!';
    const lOGOUT_MSG = 'You are logged out!';


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'api/v1/home';


    private $banChecker;


    private $logService;


    private $redirectService;


    /**
     * LoginController constructor.
     * @param BanCheckerService $banChecker
     * @param LogService $logService
     * @param RedirectService $redirectService
     */
    public function __construct(BanCheckerService $banChecker, LogService $logService,
                                RedirectService $redirectService)
    {
        $this->middleware('guest')->except('logout');
        $this->banChecker = $banChecker;
        $this->logService = $logService;
        $this->redirectService = $redirectService;
    }


    /**
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticated(Request $request, $user)
    {
        if ($this->banChecker->banned($user)) {
            Auth::logout();

            return $this->redirectService->redirectWithFlash(
                $request, 'welcome', 'status', self::BAN_MSG
            );
        } else {
            $this->logService->log($user,'User login');
        }
    }


    /**
     * Log if logout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->logService->log($request->user(),'User logout');

        Auth::logout();

        return $this->redirectService->redirectWithFlash(
            $request, 'welcome', 'status', self::lOGOUT_MSG
        );
    }

}
