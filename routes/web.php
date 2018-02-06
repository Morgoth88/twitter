<?php

use App\Services\DbCleanService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 *welcome page route
 ************************************************************************************/
Route::get('/', function(DbCleanService $DbCleanService)
{
    try {
        $DbCleanService->PeriodicLogClean();
        $DbCleanService->periodicOldRecordsClean();
    }catch (InvalidArgumentException $exception){
    }
    return view('welcome');
})->name('welcome');


Route::prefix('api/v1')->group(function()
{

    /**
     *Authentication routes
     *
     **************************************************************************/
    Auth::routes();

    /**
     * Account updates routes
     *
     **************************************************************************/
    //Account update form route
    Route::get('/account/{user}', 'PageController@AccountUpdatePage')
        ->name('accountUpdateForm');
    //Account update route
    Route::put('/account/{user}', 'UserController@update')
        ->name('accountUpdate');


    /**
     * User info page for Admin
     *
     **************************************************************************/
    Route::get('info/user/{user}', 'UserController@showUser')
        ->name('getUser');
    Route::get('/user/{user}', 'PageController@UserPage')
        ->name('showUser');


    /**
     * Ban routes
     *
     **************************************************************************/
    Route::get('/ban/user/{user}', 'UserController@ban')
        ->name('userBan');
    Route::get('/ban/message/{message}', 'messageController@ban')
        ->name('messageBan');
    Route::get('/ban/message/{message}/comment/{comment}', 'commentController@ban')
        ->name('commentBan');


    /**
     * home route
     *
     **************************************************************************/
    Route::get('/home', 'PageController@indexPage')
        ->name('index');


    /**
     * Tweet routes
     *
     **************************************************************************/
    //Create message
    Route::post('/tweet', 'MessageController@create')
        ->name('wsCreateComment');
    //Read messages
    Route::get('/tweet', 'MessageController@read')
        ->name('readTweet');
    //update message
    Route::put('/tweet/{message}', 'MessageController@update')
        ->name('wsUpdateTweet');
    //delete message
    Route::delete('/tweet/{message}', 'MessageController@delete')
        ->name('deleteTweet');


    /**
     * Comment routes
     *
     **************************************************************************/
    //Create comment
    Route::post('/tweet/{message}/comment', 'CommentController@create')
        ->name('wsCreateComment');
    //get all comments
    Route::get('/tweet/{message}/comment', 'CommentController@read')
        ->name('getComments');
    //Update comment
    Route::put('/tweet/{message}/comment/{comment}', 'CommentController@update')
        ->name('wsUpdateComment');
    //Delete comment
    Route::delete('/tweet/{message}/comment/{comment}', 'CommentController@delete')
        ->name('wsDeleteComment');
});