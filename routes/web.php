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

Route::get('/', [
    'uses'=>'HomeController@index',
    'as'=>'home'
]);

/*
 * Auth Routing
 */
Route::get('/signup', [
    'uses' => 'AuthController@getSignup',
    'as'   => 'auth.signup',
    'middleware' => ['guest']
]);

Route::post('/signup', [
    'uses' => 'AuthController@postSignup',
    'middleware' => ['guest']
]);

Route::get('/signin', [
    'uses' => 'AuthController@getSignin',
    'as'   => 'auth.signin',
    'middleware' => ['guest']
]);

Route::post('/signin', [
    'uses' => 'AuthController@postSignin',
    'middleware' => ['guest']
]);

Route::get('/signout', [
    'uses' => 'AuthController@getSignout',
    'as'   => 'auth.signout'
]);

/*
 * Search Routing
 */
Route::get('/search', [
    'uses'  => 'SearchController@getResults',
    'as'    => 'search.results'
]);

/*
 * User Profile
 */
Route::get('/user/{username}', [
    'uses'  => 'ProfileController@getProfile',
    'as'    => 'profile.index'
]);

Route::get('/profile/edit', [
    'uses'  => 'ProfileController@getEdit',
    'as'    => 'profile.edit',
    'middleware' => ['auth']
]);

Route::post('/profile/edit', [
    'uses'  => 'ProfileController@postEdit',
    'middleware' => ['auth']
]);

Route::post('/profile/delete-photo', [
    'uses'  => 'ProfileController@postDeletePhoto',
    'as'    => 'profile.delete-photo',
    'middleware' => ['auth']
]);

/*
 * Friend Routes
 */
Route::get('/friends', [
    'uses'  => 'FriendController@getIndex',
    'as'	=> 'friends.index',
    'middleware' => ['auth']
]);

Route::get('/friends/add/{username}', [
    'uses'  => 'FriendController@getAdd',
    'as'	=> 'friends.add',
    'middleware' => ['auth']
]);

Route::get('/friends/accept/{username}', [
    'uses'  => 'FriendController@getAccept',
    'as'	=> 'friends.accept',
    'middleware' => ['auth']
]);

Route::post('/friends/delete/{username}', [
    'uses'  => 'FriendController@postDelete',
    'as'	=> 'friends.delete',
    'middleware' => ['auth']
]);

Route::get('/friends/reject/{username}', [
    'uses'  => 'FriendController@getReject',
    'as'	=> 'friends.reject',
    'middleware' => ['auth']
]);

/*
 * Status Routing
 */
Route::post('/status', [
    'uses'  => 'StatusController@postStatus',
    'as'	=> 'status.post',
    'middleware' => ['auth']
]);

Route::get('/status/{statusId}', [
    'uses'  => 'StatusController@getStatus',
    'as'	=> 'status.get',
    'middleware' => ['auth']
]);

Route::post('/status/{statusId}/reply', [
    'uses'  => 'StatusController@postReply',
    'as'	=> 'status.reply',
    'middleware' => ['auth']
]);

Route::post('/status/{statusId}/like', [
    'uses'  => 'StatusController@postLike',
    'as'	=> 'status.like',
    'middleware' => ['auth']
]);

Route::post('/status/{statusId}/edit', [
    'uses'  => 'StatusController@postEditStatus',
    'as'	=> 'status.edit',
    'middleware' => ['auth']
]);

Route::get('/status/{statusId}/delete', [
    'uses'  => 'StatusController@getDeleteStatus',
    'as'	=> 'status.delete',
    'middleware' => ['auth']
]);

Route::get('/status/{statusId}/likers', [
    'uses'  => 'StatusController@getStatusLikers',
    'as'	=> 'status.likers',
    'middleware' => ['auth']
]);