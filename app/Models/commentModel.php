<?php

namespace App\Models;

use App\Comment;
use Illuminate\Database\Eloquent\Model;


class commentModel extends Model
{

    /**
     * create message comment and update message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function createComment ($request, $message) {
        $comment = $request->user()->comment()
            ->create([
                'text' => htmlspecialchars($request->comment,ENT_QUOTES),
                'message_id' => $message->id
            ]);

        $message->updated_at = now();
        $message->save();

        return $comment;
    }


    /**
     * return message with all comments sorted by created at
     *
     * @param $message
     * @return mixed
     */
    public function getAllComments($message)
    {
        $comms = Comment::with('user')->where([['message_id', $message->id],['old', 0 ]])->get();
        return $comms;
    }


    /**
     * create new comment from old
     *
     * @param $request
     * @param $comment
     * @param $message
     * @return mixed
     */
    public function updateComment($request, $comment, $message)
    {
        $newComment = $request->user()->comment()->create([
            'text' => htmlspecialchars($request->comment,ENT_QUOTES),
            'old_id' => $comment->id,
            'created_at' => $comment->created_at,
            'message_id' => $message->id]);

        $comment->old = 1;
        $comment->save();

        return $newComment;
    }
}
