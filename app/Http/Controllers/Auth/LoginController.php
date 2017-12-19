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

    public function authenticated (Request $request, $user) {

        $activity_log = new Activity_log();

        $activity_log->user_id = $user->id;
        $activity_log->activity = 'User login';
        $activity_log->save();

    }

    public function logout (Request $request) {
        $activity_log = new Activity_log();

        $activity_log->user_id = $request->user()->id;
        $activity_log->activity = 'User logout';
        $activity_log->save();

        Auth::logout();

        return redirect(route('welcome'));

    }

}
