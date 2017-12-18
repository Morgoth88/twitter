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
    return view('welcome');
})->name('welcome');

Route::prefix('api/v1')->group(function () {

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/account', 'userController@showAccountUpdateForm')->name('accountUpdateForm');

    Route::put('/account', 'userController@update')->name('accountUpdate');

});