<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('frontend');
});

Route::resource('feed', 'FeedController', [ 'except' => ['create', 'edit'] ]);
Route::resource('feed.article', 'ArticleController', [ 'only' => ['index', 'show'] ]);