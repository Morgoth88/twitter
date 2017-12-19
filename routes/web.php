<?php

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

Route::get('/', function () {
    \App\Activity_log::periodic_log_delete();
    return view('welcome');
})->name('welcome');


Route::prefix('api/v1')->group(function () {

    Auth::routes();

    /**
     * Account updates routes
     */
    //Acount update form route
    Route::get('/account', 'userController@showAccountUpdateForm')->name('accountUpdateForm');
    //Acount update route
    Route::put('/account', 'userController@update')->name('accountUpdate');


    /**
     * tweet routes
     */
    Route::post('/tweet','messageController@create')->name('createTweet');

    Route::get('/tweet','messageController@read')->name('readTweet');

    Route::put('/tweet','messageController@update')->name('updateTweet');

});