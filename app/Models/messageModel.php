<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Comment;
use App\TimeHelper;

class messageModel extends Model
{

    /**
     * return all messages sorted by updated at, with all message comments
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMessages () {
        $messages = Message::with(['comment' => function ($q) {
            $q->where('old', 0)->with('user')->orderBy('created_at', 'desc');
        }])->where('old', 0)
            ->with('user')
            ->orderBy('message.updated_at', 'desc')
            ->paginate(5);

        return $messages;
    }

    /**
     * create new tweet
     *
     * @param $request
     * @return mixed
     */
    public function createMessage ($request) {

        $tweet = $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet, ENT_QUOTES)
        ]);

        return $tweet;
    }


    /**
     * create new message, hide old message and transfer old message comments to new message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function updateMessage($request, $message){

        $newMessage = $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet,ENT_QUOTES),
            'old_id' => $message->id,
            'created_at' => $message->created_at
        ]);

        foreach ($message->comment as &$comment) {
            $comment->message_id = $newMessage->id;
            $comment->save();
        }

        $message->old = 1;
        $message->save();

        return $newMessage;
    }


    /**
     * clean old messages and comments from DB
     * every week
     */
    public static function periodicDbClean () {

        $oldestMess = Message::where('old', 1)
            ->min('message.updated_at');

        $oldestComm = Comment::where('old', 1)
            ->min('comment.updated_at');;

        if (!is_null($oldestMess) && TimeHelper::weekPassed($oldestMess)) {

            $messages = Message::where('old', 1)
                ->get();

            $messages->each(function ($mess, $key) {
                $mess->delete();
            });
        }

        if (!is_null($oldestComm) && TimeHelper::weekPassed($oldestComm)) {

            $comment = Comment::where('old', 1)
                ->get();

            $comment->each(function ($comm, $key) {
                $comm->delete();
            });
        }
    }
}
