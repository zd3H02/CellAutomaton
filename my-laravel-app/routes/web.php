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


Route::get(' /home'             , 'HomeController@index')->name('home');
Route::post('/home'             , 'HomeController@index');
Route::post('/home/create'      , 'HomeController@create');
Route::post('/home/del'         , 'HomeController@del');
Route::get('/home/trashcan'    , 'HomeController@forcedel');
Route::post('/home/trashcan'    , 'HomeController@trashcan');
Route::post('/home/forcedel'    , 'HomeController@forcedel');

// Route::post('/garbagecan', 'HomeController@garbagecan');

Route::get('/world', 'WorldController@index');

Route::get( '/local'                ,   'LocalController@index');
Route::post('/local'                ,   'LocalController@index');
Route::post('/local/first'          ,   'LocalController@first');
Route::post('/local/calc'           ,   'LocalController@calc');
Route::post('/local/codesave'       ,   'LocalController@codesave');
Route::post('/local/cellcolorsave'  ,   'LocalController@cellcolorsave');
Route::post('/local/change'         ,   'LocalController@save');


// Route::post('/local', 'LocalController@index');
// Route::get('/local', 'LocalController@index');
// Route::get('/local/run', 'LocalController@get');
// Route::post('/local/run', 'LocalController@run');
// Route::post('/local/stop', 'LocalController@stop');
// Route::post('/local/save', 'LocalController@save');