<?php

namespace App\Http\Controllers;


use App\Ban;
use App\Events\MessageBanned;
use App\Events\MessageDeleted;
use App\Events\MessageUpdated;
use App\Events\newMessageCreated;
use App\User;
use App\Interfaces\MessageInterface;
use Illuminate\Http\Request;
use App\Message;
use App\TimeHelper;
use App\Models\messageModel;

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

        $messageModel = new messageModel();
        $messages = $messageModel->getMessages();

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

        $messageModel = new messageModel();
        $tweet = $messageModel->createMessage($request);

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

            $messageModel = new messageModel();
            $newMessage =  $messageModel->updateMessage($request, $Message);

            event(new MessageUpdated($newMessage, $newMessage->user));

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

        $this->authorize('update_delete', $message);

        if (TimeHelper::lessThanTwoMinutes($message)) {

            event(new MessageDeleted($message->id));

            $message->delete();

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

            event(new MessageBanned($message));

            $request->session()->flash('status', self::SUCC_TW_BAN);
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', self::UNAUTH);
            return redirect(route('readTweet'));
        }

    }
}