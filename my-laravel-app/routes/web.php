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
Route::post('/home', 'HomeController@post');
Route::post('/home/del', 'HomeController@del');

Route::get('/world', 'WorldController@index');

Route::post('/local', 'LocalController@index');
Route::get('/local', 'LocalController@index');
Route::get('/local/run', 'LocalController@get');
Route::post('/local/run', 'LocalController@run');
Route::post('/local/run', 'LocalController@stop');
Route::post('/local/save', 'LocalController@stop');