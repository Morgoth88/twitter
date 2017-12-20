<?php

namespace App\Policies;

use App\Message;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class messagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
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
    public function update_delete(User $user, Message $message){
        return $user->id === $message->user_id;
    }

}
