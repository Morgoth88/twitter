<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class userController extends Controller
{

    public function __construct () {

        $this->middleware('auth');
    }



    public function showAccountUpdateForm (Request $request) {

        $user = $request->user();
        return view('auth.account_update')->with('user', $user);
    }



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

            $request->session()->flash('status','Account has been successfully updated');
            return redirect(route('home'));
        }
        else{

            $request->session()->flash('error','password does not match');
            return redirect(route('accountUpdateForm'));
        }

    }
}
