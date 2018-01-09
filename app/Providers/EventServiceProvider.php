<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\newMessageCreated' => [
            'App\Listeners\newMessageListener',
        ],
        'App\Events\MessageDeleted' => [
            'App\Listeners\messageDeleteListener',
        ],
        'App\Events\MessageUpdated' => [
            'App\Listeners\messageUpdateListener',
        ],
        'App\Events\MessageBanned' => [
            'App\Listeners\messageBannedListener',
        ],
        'App\Events\newCommentCreated' => [
            'App\Listeners\newCommentListener',
        ],
        'App\Events\CommentDeleted' => [
            'App\Listeners\commentDeleteListener',
        ],
        'App\Events\CommentBanned' => [
            'App\Listeners\commentBannedListener',
        ],
        'App\Events\CommentUpdated' => [
            'App\Listeners\commentUpdateListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot () {
        parent::boot();

        //
    }
}
