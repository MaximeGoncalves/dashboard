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


Auth::routes();

//Route::get("{path}", "DashboardController@index")->where('path', "([A-z\d-\/_.]+)?");
Route::get("{path}", "DashboardController@index")->where('path', "[\/\w\.-]*");

Route::get('/home', 'HomeController@index')->name('home');



