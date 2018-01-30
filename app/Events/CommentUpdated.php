<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentUpdated implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;


    public $user;


    /**
     * CommentUpdated constructor.
     * @param $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
        $this->user = $comment->user;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('commentUpdate');
    }


    public function broadcastAs()
    {
        return 'cmntUpdt';
    }


    public function broadcastWith()
    {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'message_id' => $this->comment->message_id,
                'old' => $this->comment->old,
                'old_id' => $this->comment->old_id,
                'text' => $this->comment->text,
                'created_at' => $this->comment->created_at,
                'updated_at' => $this->comment->updated_at,
            ],
            'user' => [
                'user_id' => $this->user->id,
                'userName' => $this->user->name,
                'userRole' => $this->user->role_id,
            ]
        ];
    }
}
