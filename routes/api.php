<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('user', 'API\UserController');
    Route::apiResource('society', 'API\SocietyController');
    Route::apiResource('tickets', 'API\TicketController');
    Route::get('/profile', 'API\UserController@profile');
    Route::put('/updateProfile', 'API\UserController@updateProfile');
    Route::post('/uploadFiles', 'API\TicketController@uploadFiles');
    Route::post('/addAction/{ticket}', 'API\ActionsController@store');
    Route::get('/message', 'API\MessageController@index');
    Route::post('/message/{ticket}', 'API\MessageController@store');
    Route::put('/message/{message}', 'API\MessageController@update');
    Route::get('/tickets/filter', 'API\TicketController@filter');
    Route::apiResource('/knowledges', 'API\KnowledgesController');
});
