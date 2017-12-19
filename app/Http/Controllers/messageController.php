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

        $request->session()->flash('status','Tweet has been successfully created');
        return response(redirect(route('readTweet')), 201);
    }


    public function read () {

        $tweets = Message::orderBy('created_at', 'desc')->paginate(20);

        return view('home')->with('tweets', $tweets);
    }


    public function update (Request $request, $id) {

        $originalMessage = Message::where('id', $id)->first();


        if ($originalMessage) {

            $this->authorize('update_delete', $originalMessage);

            if (time() - strtotime($originalMessage->created_at) <= 120) {

                $this->validate($request, [
                    'tweet' => 'required|string'
                ]);

                $newMessage= $request->user()->message()->create([
                    'text' => $request->tweet,
                    'old_id' =>  $originalMessage->id
                ]);

                $request->session()->flash('status','Tweet has been successfully updated');
                return redirect(route('readTweet'));
            }
            else
            {
                $request->session()->flash('status','Sorry, time to update has expired');
                return redirect(route('readTweet'));
            }
        }

    }

    public function delete(Request $request,$id){
        $message = Message::where('id', $id)->first();

        if($message) {
            $this->authorize('update_delete', $message);

            if (time() - strtotime($message->created_at) <= 120) {

                $message->delete();

                $request->session()->flash('status', 'Tweet was successfully deleted');
                return redirect(route('readTweet'));
            }
        }
    }
}