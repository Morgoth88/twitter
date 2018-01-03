<?php

namespace App\Http\Controllers;


use App\User;
use App\Interfaces\MessageInterface;
use Illuminate\Http\Request;
use App\Message;
use App\TimeHelper;

class messageController extends Controller implements MessageInterface
{

    /**
     * messageController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }


    /**
     * Read all messages...20 per page
     *
     * @return $this
     */
    public function read () {

        $messages = Message::with(['comment' => function($q){
            $q->where('old', 0)->orderBy('created_at','desc');
        }])->where('old', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('home')->with('tweets', $messages);
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

        $tweet = $request->user()->message()->create([
            'text' => $request->tweet
        ]);

        $request->session()->flash('status', 'Tweet has been successfully created');
        return redirect(route('readTweet'));
    }


    /**
     * update message
     *
     * @param Request $request
     * @param Message $Message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update (Request $request, Message $Message) {

        $this->authorize('update_delete', $Message);

        if (TimeHelper::lessThanTwoMinutes($Message)) {

            $this->validate($request, [
                'tweet' => 'required|string'],
                ['Tweet is empty!']);

            $newMessage = $request->user()->message()->create([
                'text' => $request->tweet,
                'old_id' => $Message->id,
                'created_at' => $Message->created_at
            ]);

            foreach ($Message->comment as &$comment) {
                $comment->message_id = $newMessage->id;
                $comment->save();
            }

            $Message->old = 1;
            $Message->save();

            $request->session()->flash('status', 'Tweet has been successfully updated');
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', 'Sorry, time to update has expired');
            return redirect(route('readTweet'));
        }
    }


    /**
     * Delete message
     *
     * @param Request $request
     * @param Message $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete (Request $request, Message $message) {

        $this->authorize('update_delete', $message);

        if (TimeHelper::lessThanTwoMinutes($message)) {

            $message->delete();

            $request->session()->flash('status', 'Tweet was successfully deleted');
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', 'Sorry, time limit expired!');
            return redirect(route('readTweet'));
        }
    }


    public function ban(Message $message, Request $request){

        $user = User::where('id', $message->user_id)->first();

        if($request->user()->role_id == 1 && $user->role_id != 1)
        {
            $message->text = 'Message was banned!';
            $message->old = 1;
            $message->save();

            $request->session()->flash('status','Message was successfully banned');
            return redirect(route('readTweet'));
        }
        else{
            $request->session()->flash('error','Unauthorized action');
            return redirect(route('readTweet'));
        }

    }
}