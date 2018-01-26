<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\MessageCreated' => [
            'App\Listeners\NewMessageListener',
        ],
        'App\Events\MessageDeleted' => [
            'App\Listeners\MessageDeletedListener',
        ],
        'App\Events\MessageUpdated' => [
            'App\Listeners\MessageUpdateDListener',
        ],
        'App\Events\MessageBanned' => [
            'App\Listeners\MessageBannedListener',
        ],
        'App\Events\CommentCreated' => [
            'App\Listeners\NewCommentListener',
        ],
        'App\Events\CommentDeleted' => [
            'App\Listeners\CommentDeletedListener',
        ],
        'App\Events\CommentBanned' => [
            'App\Listeners\CommentBannedListener',
        ],
        'App\Events\CommentUpdated' => [
            'App\Listeners\CommentUpdatedListener',
        ],
        'App\Events\UserBanned' => [
            'App\Listeners\UserBannedListener',
        ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
