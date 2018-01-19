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
    public function index () {

        return view('home');
    }


    /**
     * Read all messages...20 per page
     *
     * @return $this
     */
    public function read () {

        $messageModel = new messageModel();
        $messages = $messageModel->getMessages();

        return response($messages->toJson() , 200)
            ->header('Content-Type', 'application/json');
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

        return response($tweet->toJson() , 201)
            ->header('Content-Type', 'application/json');
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

            return response($newMessage->toJson() , 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Time limit to update expired') , 401)
                ->header('Content-Type', 'application/json');
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

            $id = $message->id;
            $message->delete();

            return response(json_encode("message : Tweet $id was deleted"), 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Time limit to update expired') , 401)
                ->header('Content-Type', 'application/json');
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

            return response(json_encode("message : Tweet $message->id was banned"), 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Unauthorized action') , 401)
                ->header('Content-Type', 'application/json');
        }

    }
}