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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/store/post', 'PostsController@store_post')->name('storePost');

Route::get('{postId}', 'PostsController@show_post')->name('showPost');

Route::get('posts/{userId}', 'PostsController@index')->name('showPosts');

Route::post("/edit/post","PostsController@edit_post")->name("editPost");

Route::post("update/post","PostsController@update_post")->name("updatePost");

Route::post("/destroy/post", "PostsController@destroy_post")->middleware("check_user")->name("destroyPost");

Route::post('destroy/comment', 'CommentsController@destroy_comment')->name('destroyComment');

Route::get('{userId}/{userName}', 'ProfileController@index')->name('showProfile');

Route::post('/store/user/data', 'ProfileController@store_user_data')->name('storeUserData');

Route::post('/store/comment', 'CommentsController@store_comment')->name('storeComment');

Route::post('/like', 'LikesController@store_like')->name('like');

Route::post('/follow', 'FollowersController@follow')->name('follow');
