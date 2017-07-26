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

Route::get('/', 'PostController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('admin', 'AdminController');


Route::get('/changePassword', 'Auth\ChangePasswordController@edit');
Route::patch('/changePassword', 'Auth\ChangePasswordController@update');


Route::get('/student', 'StudentController@index');
Route::get('/student/{student}/post', 'StudentController@studentPost');
Route::post('/student/{student}/post/search', 'StudentController@searchPost');

Route::post('/post/search', 'PostController@search');
Route::post('/post/calendar/{year}/{month}', 'PostController@calendar');

Route::get('/post/date/{year}', 'PostController@datePosts');
Route::get('/post/date/{year}/{month}', 'PostController@datePosts');
Route::get('/post/date/{year}/{month}/{day}', 'PostController@datePosts');
Route::resource('post', 'PostController');

Route::get('/register/dynamic', 'Auth\RegisterController@dynamic');
Route::post('/passwordCheck', 'Auth\RegisterController@passwordCheck');