<?php

namespace App\Http\Controllers;

use App\Activity_log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class userController extends Controller
{

    /**
     * userController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }


    /**
     * Show account update form with current user data
     *
     * @param Request $request
     * @return $this
     */
    public function showAccountUpdateForm (Request $request) {

        $user = $request->user();
        return view('auth.account_update')->with('user', $user);
    }


    /**
     * User's account update and activity log
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update (Request $request) {

        $user = User::where('id',$request->user()->id)->first();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            Rule::unique('users')->ignore($user->id),

            'new_password' => 'required|string|min:6'
        ]);

        if(Hash::check($request->password,$user->password )) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->new_password);

            $user->save();

            $activity_log = new Activity_log();

            $activity_log->user_id = $request->user()->id;
            $activity_log->activity = 'Account settings update';
            $activity_log->save();

            $request->session()->flash('status','Account has been successfully updated');
            return redirect(route('readTweet'));
        }
        else{

            $request->session()->flash('error','password does not match');
            return redirect(route('accountUpdateForm'));
        }

    }

    /**
     * ban selected user
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(User $user, Request $request){

        if($request->user()->role_id == 1 && $user->role_id != 1)
        {
            foreach ($user->message as &$message){
                $message->text = 'The user was banned!';
                $message->old = 1;
                $message->save();
            }
            foreach ($user->comment as & $comment) {
                $comment->text = 'The user was banned!';
                $comment->old = 1;
                $comment->save();
            }

            DB::table('sessions')->where('user_id', $user->id)->delete();

            $user->ban = 1;
            $user->save();

            $activity_log = new Activity_log();
            $activity_log->user_id = $request->user()->id;
            $activity_log->activity = 'Ban user: '.$user->id;
            $activity_log->save();

            $request->session()->flash('status','User was successfully banned');
            return redirect(route('readTweet'));
        }
        else{
            $request->session()->flash('error','Unauthorized action');
            return redirect(route('readTweet'));
        }
    }
}
