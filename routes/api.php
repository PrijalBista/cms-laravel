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

	Route::middleware('auth:sanctum')->group(function(){
		// Post
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

		// Dashboard
		Route::get('dashboard', 'DashboardController@getCounts');
		Route::get('posts/page', 'DashboardController@paginatedPosts');
		Route::get('feeds/page', 'DashboardController@paginatedFeeds');


		// Job
		Route::get('jobs', 'JobController@index');
		Route::post('jobs/store', 'JobController@store');
		Route::get('jobs/show/{job}', 'JobController@show');
		Route::post('jobs/update/{job}', 'JobController@update');
		Route::post('jobs/destroy/{job}', 'JobController@destroy');

		// Vacancy
		Route::get('vacancies', 'VacancyController@index');
		Route::post('vacancies/store', 'VacancyController@store');
		Route::get('vacancies/show/{vacancy}', 'VacancyController@show');
		Route::post('vacancies/update/{vacancy}', 'VacancyController@update');
		Route::post('vacancies/destroy/{vacancy}', 'VacancyController@destroy');

		// Feedback
		Route::get('feedbacks', 'FeedbackController@index');
		Route::post('feedbacks/store', 'FeedbackController@store');
		Route::get('feedbacks/show/{feedback}', 'FeedbackController@show');
		Route::post('feedbacks/update/{feedback}', 'FeedbackController@update');
		Route::post('feedbacks/destroy/{feedback}', 'FeedbackController@destroy');

		// Carousel (Uses Photo Model)
		Route::get('carousels', 'CarouselController@index');
		Route::post('carousels/store', 'CarouselController@store');
		Route::get('carousels/show/{photo}', 'CarouselController@show');
		Route::post('carousels/update/{photo}', 'CarouselController@update');
		Route::post('carousels/destroy/{photo}', 'CarouselController@destroy');

		// Project
		Route::get('projects', 'ProjectController@index');
		Route::post('projects/store', 'ProjectController@store');
		Route::get('projects/show/{project}', 'ProjectController@show');
		Route::post('projects/update/{project}', 'ProjectController@update');
		Route::post('projects/destroy/{project}', 'ProjectController@destroy');

		// Cover (Uses Photo Model)
		Route::get('covers', 'CoverController@index');
		Route::get('covers/show/{photo}', 'CoverController@show');
		Route::post('covers/update/{photo}', 'CoverController@update');

		// Project
		Route::get('shares', 'ShareController@index');
		Route::post('shares/store', 'ShareController@store');
		Route::get('shares/show/{share}', 'ShareController@show');
		Route::post('shares/update/{share}', 'ShareController@update');
		Route::post('shares/destroy/{share}', 'ShareController@destroy');

		// User
		Route::get('users', 'Auth\UserController@index');
		Route::post('users/store', 'Auth\UserController@store');
		Route::get('users/show/{user}', 'Auth\UserController@show');
		Route::post('users/update/{user}', 'Auth\UserController@update');
		Route::post('users/destroy/{user}', 'Auth\UserController@destroy');

		// Auth
		Route::post('users/logout', 'Auth\UserController@logout');

	});

	Route::post('users/login', 'Auth\UserController@login');
	Route::get('downloadmedia/{media}', 'ShareController@downloadMedia');
});