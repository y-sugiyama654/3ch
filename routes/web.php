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

use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['verify' => true]);

Route::resource('discussions', 'DiscussionsController');

Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('/login/callback/github', 'Auth\LoginController@handleProviderCallback');

// ログインユーザー対象権限
Route::group(['middleware' => ['auth', 'can:allUser']], function () {
    Route::resource('discussions/{discussion}/replies', 'RepliesController');

    Route::get('users/notifications', [UsersController::class, 'notifications'])->name('users.notifications');
    Route::post('discussions/{discussion}/replies/{reply}/mark-as-best-reply', 'DiscussionsController@reply')->name('discussions.best-reply');

    Route::get('/reply/like/{id}', 'RepliesController@like')->name('reply.like');
    Route::get('/reply/unlike/{id}', 'RepliesController@unlike')->name('reply.unlike');

    Route::get('/reply/edit/{id}', 'RepliesController@edit')->name('reply.edit');
    Route::put('/reply/update/{id}', 'RepliesController@update')->name('reply.update');

    Route::get('/discussion/watch/{id}', 'WatchersController@watch')->name('discussions.watch');
    Route::get('/discussion/unwatch/{id}', 'WatchersController@unwatch')->name('discussions.unwatch');
});

// 管理者対象権限
Route::group(['middleware' => ['auth', 'can:admin']], function () {
    Route::resource('channels', 'ChannelsController');
});

