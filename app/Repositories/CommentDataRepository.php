<?php

namespace App\Repositories;

use App\Comment;
use App\Interfaces\CommentRepositoryInterface;

class CommentDataRepository extends AbstractRepository implements CommentRepositoryInterface
{

    /**
     * return message with all comments
     *
     * @param $message
     * @return mixed
     */
    public function getAllComments($message)
    {
        return Comment::with('user')
            ->where([['message_id', $message->id], ['old', 0]])
            ->get();
    }


    /**
     * create message comment and update message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function createComment($request, $message)
    {
        $comment = $request->user()->comment()
            ->create([
                'text' => $request->comment,
                'message_id' => $message->id
            ]);

        $message->updated_at = now();
        $message->save();

        return $comment;
    }


    /**
     * create new comment from old
     *
     * @param $request
     * @param $comment
     * @return mixed
     */
    public function updateComment($request, $comment)
    {
        $newComment = $request->user()->comment()
            ->create([
                'text' => $request->comment,
                'old_id' => $comment->id,
                'created_at' => $comment->created_at,
                'message_id' => $comment->message->id
            ]);

        $comment->old = 1;
        $comment->save();

        return $newComment;
    }


    /**
     * return count of actual comments
     *
     * @param $message
     * @return mixed
     */
    public function getCommentsCount($message)
    {
        return $message->comment()->where('old', 0)->count();
    }


    /**
     * return records where old = 1
     * @return mixed
     */
    public function getOldRecords()
    {
        return Comment::where('old', 1)->get();
    }


    /**
     * get oldest record time
     * @return mixed
     */
    public function getOldestRecord()
    {
        return Comment::where('old', 1)->min('updated_at');
    }

}
