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

//Route::get('/', function () {
//    return view('welcome');
//});
Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
Route::get("{path}", "DashboardController@index")->where('path', "([A-z\d-\/_.]+)?");
//Route::any('{query?}', "DashboardController@index")->where('query', '.*');
//Route::get('/{catchall?}', ['as' => 'start', 'middleware' => 'auth', function() {
//    return view('layouts.master');
//}])->where('catchall', '.*');
//Route::get('/users', 'UserController@index')->name('user.index');
//Route::get('/', 'DashboardController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');



