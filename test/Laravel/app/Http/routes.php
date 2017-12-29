<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login','TestController@login');
Route::post('login','TestController@login');

Route::get('/nota','TestController@nota');
Route::post('nota','TestController@nota');

Route::get('/daftar','TestController@daftar');
Route::post('daftar','TestController@daftar');

Route::get('/loginarray','TestController@loginarray');
Route::post('loginarray','TestController@loginarray');

Route::get('/detail_nota','TestController@detail_nota');
Route::post('detail_nota','TestController@detail_nota');
