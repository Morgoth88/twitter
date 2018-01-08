<?php

namespace App\Http\Controllers;


use App\Ban;
use App\Events\MessageDeleted;
use App\Events\newMessageCreated;
use App\User;
use App\Interfaces\MessageInterface;
use Illuminate\Http\Request;
use App\Message;
use App\TimeHelper;

class messageController extends Controller implements MessageInterface
{

    const SUCC_TW_CRT = 'Tweet has been successfully created',
        SUCC_TW_UPDT = 'Tweet has been successfully updated',
        TIME_EXP = 'Sorry, time limit expired!',
        SUCC_TW_DEL = 'Tweet was successfully deleted',
        SUCC_TW_BAN = 'Message was successfully banned',
        UNAUTH = 'Unauthorized action';

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

        $messages = Message::with(['comment' => function ($q) {
            $q->where('old', 0)->orderBy('created_at', 'desc');
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

        event(new newMessageCreated($tweet, $tweet->user, $request->user()));

        $request->session()->flash('status', self::SUCC_TW_CRT);
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

            $request->session()->flash('status', self::SUCC_TW_UPDT);
            return redirect(route('readTweet'));

        } else {
            $request->session()->flash('error', self::TIME_EXP);
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

        $msgId = $message->id;

        $this->authorize('update_delete', $message);

        if (TimeHelper::lessThanTwoMinutes($message)) {

            $message->delete();

            event(new MessageDeleted($msgId));

            $request->session()->flash('status', self::SUCC_TW_DEL);
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', self::TIME_EXP);
            return redirect(route('readTweet'));
        }
    }


    /**
     * ban message
     *
     * @param Message $message
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban (Message $message, Request $request) {

        $user = User::where('id', $message->user_id)->first();
        $ban = new Ban();

        if ($request->user()->role_id == 1 && $user->role_id != 1) {
            $ban->banPost($message);

            $request->session()->flash('status', self::SUCC_TW_BAN);
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', self::UNAUTH);
            return redirect(route('readTweet'));
        }

    }
}