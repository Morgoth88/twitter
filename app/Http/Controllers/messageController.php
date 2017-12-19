<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Message;

class messageController extends Controller
{

    /**
     * userController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create (Request $request) {

        $this->validate($request, [
            'tweet' => 'required|string'
        ]);

        $msgModel = new Message();

        $tweet = $request->user()->message()->create([
            'text' => $request->tweet
        ]);

        return response(redirect(route('readTweet')), 201);
    }



    public function read () {

        $tweets = Message::orderBy('created_at','desc')->paginate(20);

        return view('home')->with('tweets', $tweets);
    }

    public function  update(Request $request, $id){

        $this->validate($request, [
            'tweet' => 'required|string'
        ]);

    }
}