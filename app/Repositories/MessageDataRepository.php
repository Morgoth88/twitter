<?php

namespace App\Repositories;

use App\Message;

class MessageDataRepository extends AbstractRepository
{

    /**
     * @param $message
     */
    public function banPost($message)
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
     * return all messages sorted by updated at, with all message comments
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllPosts()
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
     * create new tweet
     *
     * @param $request
     * @return mixed
     */
    public function createPost($request)
    {
        return $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet, ENT_QUOTES)
        ]);
    }


    /**
     * create new message, hide old message and transfer old message comments to new message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function updatePost($request, $message)
    {
        $newMessage = $request->user()->message()->create([
            'text' => htmlspecialchars($request->tweet, ENT_QUOTES),
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

}
