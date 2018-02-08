<?php

namespace App\Repositories;

use App\Interfaces\MessageRepositoryInterface;
use App\Message;

class MessageDataRepository extends AbstractRepository implements MessageRepositoryInterface
{

    /**
     * create new tweet
     *
     * @param $request
     * @return mixed
     */
    public function createMessage($request)
    {
        return $request->user()->message()
            ->create([
                'text' => $request->tweet,
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
        }])
            ->where('old', 0)
            ->with('user')
            ->orderBy('message.updated_at', 'desc')
            ->paginate(5);
    }


    /**
     * create new message, hide old message and transfer old message comments to new message
     *
     * @param $request
     * @param $message
     * @return mixed
     */
    public function updateMessage($request, $message)
    {
        $newMessage = $request->user()->message()
            ->create([
                'text' => $request->tweet,
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
     * return records where old = 1
     * @return mixed
     */
    public function getOldRecords()
    {
        return Message::where('old', 1)->get();
    }


    /**
     * get oldest record time
     * @return mixed
     */
    public function getOldestRecord()
    {
        return Message::where('old', 1)->min('updated_at');
    }
}
