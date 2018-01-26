<?php

namespace App\Repositories;

use App\Comment;

class CommentDataRepository extends AbstractRepository
{

    /**
     * ban post
     * @param $post
     */
    public function banPost($post)
    {
        $post->text = self::BAN_POST_TEXT;
        $post->old = 1;
        $post->save();;
    }





    /**
     * return message with all comments sorted by created at
     *
     * @param $message
     * @return mixed
     */
    public function getAllPosts($message)
    {
        return Comment::with('user')->where([['message_id', $message->id], ['old', 0]])->get();
    }


    /**
     * create message comment and update message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function createPost($request, $message)
    {
        $comment = $request->user()->comment()
            ->create([
                'text' => htmlspecialchars($request->comment, ENT_QUOTES),
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
    public function updatePost($request, $comment)
    {
        $newComment = $request->user()->comment()->create([
            'text' => htmlspecialchars($request->comment, ENT_QUOTES),
            'old_id' => $comment->id,
            'created_at' => $comment->created_at,
            'message_id' => $comment->message->id]);

        $comment->old = 1;
        $comment->save();

        return $newComment;
    }
}