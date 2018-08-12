<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::post('/user', ['as' => 'user.register', 'uses' => 'UserController@register']);

Route::group(['prefix' => 'friend'], function() {

    Route::post('/', ['as' => 'friend.connect', 'uses' => 'FriendController@connect']);
    Route::post('/common', ['as' => 'friend.common', 'uses' => 'FriendController@common']);
    Route::post('/list', ['as' => 'friend.list', 'uses' => 'FriendController@list']);

});

Route::post('/friend', ['as' => 'friend.connect', 'uses' => 'FriendController@connect']);
Route::post('/friend/list', ['as' => 'friend.list', 'uses' => 'FriendController@list']);

Route::post('/subscribe', ['as' => 'subscribe.connect', 'uses' => 'SubscribeController@connect']);
Route::post('/block', ['as' => 'subscribe.block', 'uses' => 'SubscribeController@block']);

Route::post('/feed', ['as' => 'feed.block', 'uses' => 'FeedController@post']);