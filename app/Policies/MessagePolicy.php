<?php

namespace App\Policies;

use App\Message;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{

    use HandlesAuthorization;


    /**
     * MessagePolicy constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Check if message belongs to user
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function changeMessage(User $user, Message $message)
    {
        return $user->id === $message->user_id;
    }

}
