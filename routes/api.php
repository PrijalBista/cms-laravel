<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('Api')->group(function(){

	Route::get('posts', 'PostController@index');

	Route::post('posts/store', 'PostController@store');

	Route::get('posts/show/{post}', 'PostController@show');

	Route::post('posts/update/{post}', 'PostController@update');

	Route::post('posts/destroy/{post}', 'PostController@destroy');


	// Email
	Route::get('emails', 'EmailController@index');
	Route::post('emails/store', 'EmailController@store');
	// Route::get('emails/show/{email}', 'EmailController@show');
	// Route::post('emails/update/{email}', 'PostController@update');
	// Route::post('emails/destroy/{email}', 'PostController@destroy');

	// Feed
	Route::get('feeds', 'FeedController@index');
	Route::post('feeds/store', 'FeedController@store');
	Route::get('feeds/show/{feed}', 'FeedController@show');
	Route::post('feeds/update/{feed}', 'FeedController@update');
	Route::post('feeds/destroy/{feed}', 'FeedController@destroy');

	// Photo
	Route::get('photos', 'PhotoController@index');
	Route::post('photos/store', 'PhotoController@store');
	Route::get('photos/show/{photo}', 'PhotoController@show');
	Route::post('photos/update/{photo}', 'PhotoController@update');
	Route::post('photos/destroy/{photo}', 'PhotoController@destroy');


});