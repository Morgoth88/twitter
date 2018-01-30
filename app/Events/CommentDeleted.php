<?php

namespace App\Events;

use App\Repositories\CommentDataRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentDeleted implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;


    /**
     * CommentDeleted constructor.
     * @param $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('commentDelete');
    }


    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'cmntDel';
    }


    /**
     * @return array
     */
    public function broadcastWith()
    {
        $repo = new CommentDataRepository();
        $count = $repo->getCommentsCount($this->comment->message);

        return [
            'comment' => [
                'id' => $this->comment->id,
                'message_id' => $this->comment->message_id
            ],
            'commentsCount' => $count
        ];
    }
}
