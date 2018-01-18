<?php

use App\Activity_log;
use App\Ban;

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
Route::get('/', function () {
    Activity_log::periodic_log_delete();
    Ban::periodicBanInDbClean();
    return view('welcome');
})->name('welcome');


Route::prefix('api/v1')->group(function () {

    /**
     *Authentication routes
     ************************************************************************************/
    Auth::routes();

    /**
     * Account updates routes
     ************************************************************************************/
    //Acount update form route
    Route::get('/account', 'userController@showAccountUpdateForm')->name('accountUpdateForm');
    //Acount update route
    Route::put('/account', 'userController@update')->name('accountUpdate');


    /**
     * User info page for Admin
     ************************************************************************************/
    Route::get('/user/{user}','userController@showUser')->name('showUser');

    /**
     * Ban routes
     ************************************************************************************/
    Route::get('/ban/user/{user}', 'userController@ban')->name('userBan');
    Route::get('/ban/message/{message}','messageController@ban')->name('messageBan');
    Route::get('/ban/message/{message}/comment/{comment}','commentController@ban')->name('commentBan');


    /**
     * Tweet routes
     ************************************************************************************/
    //Create message
    Route::post('/tweet','messageController@create')->name('createComment');
    //Read messages
    Route::get('/tweet','messageController@read')->name('readTweet');
    //update message
    Route::put('/tweet/{message}','messageController@update')->name('updateTweet');
    //delete message
    Route::delete('/tweet/{message}','messageController@delete')->name('deleteTweet');


    /**
     * Comment routes
     ************************************************************************************/
    //Create comment
    Route::post('/tweet/{message}/comment','commentController@create')->name('createComment');
    //Read comment
    Route::get('/tweet/{message}/comment','commentController@read')->name('readComment');
    //Update comment
    Route::put('/tweet/{message}/comment/{comment}','commentController@update')->name('updateComment');
    //Delete comment
    Route::delete('/tweet/{message}/comment/{comment}','commentController@delete')->name('deleteComment');
});