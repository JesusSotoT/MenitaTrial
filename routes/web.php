<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'PostController@index')->name('home');
    Route::get('post-detail/{id}', 'PostController@show')->name('post-detail');
    Route::post('store-comment', 'PostController@store')->name('store-comment');
    Route::get('edit-comment/{id}/{idArticle}', 'PostController@edit')->name('edit-comment');
    Route::post('store-edit-comment', 'PostController@update')->name('store-edit-comment');
    Route::get('delete-comment/{id}/{idArticle}', 'PostController@destroy')->name('delete-comment');


});

Auth::routes();

