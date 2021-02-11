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



Route::middleware(['auth:api'])->group(function () {
    //admin/editor post related routes
    Route::post('/admin/post/create','AdminController@postDisease');
    Route::get('/admin/post/{id}/details','AdminController@getPostDetails');
    Route::post('/admin/post/{id}/delete','AdminController@deletePost');
    Route::get('/admin/{userId}/posts','AdminController@getAllDiseases');

    //system user related routes 
    Route::get('/admin/users/','AdminController@getAllUser');
    Route::get('/admin/user/{id}','AdminController@userDetails');

    //user verification route
    Route::get('/admin/user/{id}/verify','AdminController@verifyUser');
    Route::post('/admin/user/{id}/promote','AdminController@promoteUser');
});

    //user auth routes
    Route::post('/auth/login','AdminController@login');
    Route::post('/admin/user/create','AdminController@createUser');
    Route::get('/unique/{email}', 'AdminController@getEmailStatus');


    //frontend related routes
    Route::get('disesbycategory','FrontendController@diseaseByCategory');
    Route::get('post/bycategory/{category}','FrontendController@categoryDisease');
    Route::get('/post/postByName/{name}','FrontendController@showPostByName');
    Route::get('/post/{postId}/details','FrontendController@showPostDetails');
    Route::get('/posts','FrontendController@showAllPost');
    Route::get('disease/trending','FrontendController@showTrendingDiseases');
    Route::get('disease/view/top','FrontendController@showTopViewed');
    Route::get('/users','FrontendController@getUsers');