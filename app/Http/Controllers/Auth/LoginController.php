<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Activity_log;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'api/v1/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticated (Request $request, $user) {
        if($user->ban == 1)
        {
            Auth::logout();
            $request->session()->flash('status', self::BAN_MSG);
            return redirect(route('welcome'));

        }
        else {

            $activity_log = new Activity_log();

            $activity_log->user_id = $user->id;
            $activity_log->activity = 'User login';
            $activity_log->save();

            $request->session()->flash('status', 'You are logged in!');
        }
    }

    /**
     * Log if logout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout (Request $request) {
        $activity_log = new Activity_log();

        $activity_log->user_id = $request->user()->id;
        $activity_log->activity = 'User logout';
        $activity_log->save();

        Auth::logout();

        $request->session()->flash('status','You are logged out!');

        return redirect(route('welcome'));

    }

}
