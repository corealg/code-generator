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

// User Routes
Route::any("users", "UserController@index");
Route::get("user/create", "UserController@create");
Route::post("user/save", "UserController@save");
Route::post("user/update", "UserController@update");
Route::post("user/delete", "UserController@delete");
Route::get("user/edit/{id}", "UserController@edit");
Route::get("user/{id}", "UserController@view");
