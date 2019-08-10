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
});

Route::redirect('/home', '/threads');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadController@index')->name('threads');
Route::get('/threads/create', 'ThreadController@create')->middleware('must-be-confirmed');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::get('/threads/search', 'SearchController@show');


Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/threads/{channel}', 'ThreadController@index');
Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed');
Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');//->name('add_reply_to_thread');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');

Route::get('/profiles/{user}/notifications/', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

//Route::get('api/users/{user}/notifications)
//Route::resource('threads', 'ThreadController');

Route::get('/register/confirm', 'Api\RegisterConfirmationController@index');

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar'); //->middleware('auth'); instead of in controller



