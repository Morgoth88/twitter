<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Message;
use App\TimeHelper;

class messageController extends Controller
{

    /**
     * userController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }


    /**
     * create message
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create (Request $request) {

        $this->validate($request, [
            'tweet' => 'required|string'],
            ['Tweet is empty!']);

        $msgModel = new Message();

        $tweet = $request->user()->message()->create([
            'text' => $request->tweet
        ]);

        $request->session()->flash('status','Tweet has been successfully created');
        return response(redirect(route('readTweet')), 201);
    }

    /**
     * Read all messages...20 per page
     *
     * @return $this
     */
    public function read () {

        $tweets = Message::where('old', 0)->orderBy('created_at','desc')->paginate(20);

        return view('home')->with('tweets', $tweets);
    }


    /**
     * update message
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update (Request $request, $id) {

        $originalMessage = Message::where('id', $id)->first();


        if ($originalMessage) {

            $this->authorize('update_delete', $originalMessage);

            if (TimeHelper::lessThanTwoMinutes($originalMessage)) {

                $this->validate($request, [
                    'tweet' => 'required|string'],
                    ['Tweet is empty!']);

                $newMessage= $request->user()->message()->create([
                    'text' => $request->tweet,
                    'old_id' =>  $originalMessage->id,
                    'created_at' => $originalMessage->created_at
                ]);

                $originalMessage->old = 1;
                $originalMessage->save();

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

    /**
     * Delete message
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request,$id){
        $message = Message::where('id', $id)->first();

        if($message) {

            $this->authorize('update_delete', $message);

            if (TimeHelper::lessThanTwoMinutes($message)) {

                $message->delete();

                $request->session()->flash('status', 'Tweet was successfully deleted');
                return redirect(route('readTweet'));
            }else{
                $request->session()->flash('status', 'Sorry, time limit expired!');
                return redirect(route('readTweet'));
            }
        }else{
            $request->session()->flash('status', 'Unknown tweet id');
            return redirect(route('readTweet'));
        }
    }
}