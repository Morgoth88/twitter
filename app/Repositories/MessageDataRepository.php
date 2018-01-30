<?php

namespace App\Repositories;

use App\Message;

class MessageDataRepository extends AbstractRepository
{

    /**
     * @param $message
     */
    public function banMessage($message)
    {
        $message->text = self::BAN_POST_TEXT;
        $message->old = 1;
        foreach ($message->comment as &$comment) {
            $comment->old = 1;
            $comment->save();
        }
        $message->save();;
    }


    /**
     * create new tweet
     *
     * @param $request
     * @return mixed
     */
    public function createMessage($request)
    {
        return $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet, ENT_QUOTES)
        ]);
    }


    /**
     * return all messages sorted by updated at, with all message comments
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllMessages()
    {
        return Message::with(['comment' => function($q)
        {
            $q->where('old', 0)->with('user')->orderBy('created_at', 'desc');
        }])->where('old', 0)
            ->with('user')
            ->orderBy('message.updated_at', 'desc')
            ->paginate(5);
    }


    /**
     * create new message, hide old message and transfer old message comments to new message
     *
     * @param $request
     * @param $post
     * @return mixed
     */
    public function updateMessage($request, $post)
    {
        $newPost = $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet, ENT_QUOTES),
            'old_id' => $post->id,
            'created_at' => $post->created_at
        ]);

        foreach ($post->comment as &$comment) {
            $comment->message_id = $newPost->id;
            $comment->save();
        }

        $post->old = 1;
        $post->save();

        return $newPost;
    }


    /**
     * delete message
     *
     * @param $message
     * @return mixed
     */
    public function deleteMessage($message)
    {
        $id = $message->id;
        $message->delete();

        return $id;
    }

}
