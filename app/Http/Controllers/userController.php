<?php

namespace App\Http\Controllers;

use App\Activity_log;
use App\Events\Userbanned;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Ban;
use App\Message;
use App\Comment;
use App\TimeHelper;


class userController extends Controller
{

    const SUCC_ACC_UPDT = 'Account has been successfully updated',
            PASS_ERR_MATCH ='password does not match',
            SUCC_USR_BAN ='User was successfully banned',
            UNAUTH = 'Unauthorized action';

    /**
     * userController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }

    public function index(){

        return view('user');
    }

    public function showUser(User $user, Request $request){

        if($request->user()->role_id == 1)
        {

            $result['MessCount'] = Message::where([['user_id', $user->id],['old' , 0 ]])->count();
            $result['CommCount'] = Comment::where([['user_id', $user->id],['old' , 0 ]])->count();
            $result['lastCreatMess'] = TimeHelper::passedTime(Message::where([['user_id', $user->id],['old' , 0 ]])->max('created_at'));
            $result['lastCreatComm'] = TimeHelper::passedTime(Comment::where([['user_id', $user->id],['old' , 0 ]])->max('created_at'));
            $result['user'] = $user;


            return response(json_encode($result),200)
                ->header('Content-Type', 'application/json');
        }
        else{
            return response(json_encode('message : Unauthorized action'),401)
                ->header('Content-Type', 'application/json');
        }
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

            $request->session()->flash('status', self::SUCC_ACC_UPDT);
            return redirect(route('index'));
        }
        else{

            $request->session()->flash('error', self::PASS_ERR_MATCH);
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
            $ban = new Ban();

            foreach ($user->message as &$message){

                $ban->banMsg($message);
            }
            foreach ($user->comment as & $comment) {

                $ban->banCmnt($comment);
            }

            DB::table('sessions')->where('user_id', $user->id)->delete();

            $user->ban = 1;
            $user->save();

            $activity_log = new Activity_log();
            $activity_log->user_id = $user->id;
            $activity_log->activity = 'Banned by: '.$request->user()->email;
            $activity_log->save();

            event(new Userbanned($user));

            return response(json_encode("message : user $user->id was banned ") , 200)
                ->header('Content-Type', 'application/json');
        }
        else{
            return response(json_encode('message : Unauthorized action') , 401)
                ->header('Content-Type', 'application/json');;
        }
    }
}
