<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('messageDelete', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('messageUpdate', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('messageBanned', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('comment', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('commentUpdate', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('commentDelete', function () {
    return \Illuminate\Support\Facades\Auth::check();
});
Broadcast::channel('commentBanned', function () {
    return \Illuminate\Support\Facades\Auth::check();
});