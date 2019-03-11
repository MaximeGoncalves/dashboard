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

    Route::get('/analytics/getViews', 'API\AnalyticsController@getViews');
    Route::get('/analytics/getData', 'API\AnalyticsController@getData');
    Route::post('/analytics', 'API\AnalyticsController@store');

    Route::get('/user/search', 'API\UserController@search');
    Route::apiResource('user', 'API\UserController');

    Route::get('/society/search', 'API\SocietyController@search');
    Route::apiResource('society', 'API\SocietyController');

    Route::post('/uploadFiles', 'API\TicketController@uploadFiles');
    Route::get('/tickets/stats', 'API\TicketController@stats');
    Route::apiResource('tickets', 'API\TicketController');

    Route::get('/logins/search', 'API\LoginsController@search');
    Route::apiResource('logins', 'API\LoginsController');

    Route::get('/profile', 'API\UserController@profile');
    Route::put('/updateProfile', 'API\UserController@updateProfile');

    Route::get('/actions', 'API\ActionsController@index');
    Route::post('/addAction/{ticket}', 'API\ActionsController@store');

    Route::get('/message', 'API\MessageController@index');
    Route::post('/message/{ticket}', 'API\MessageController@store');
    Route::put('/message/{message}', 'API\MessageController@update');

    Route::apiResource('/knowledges', 'API\KnowledgesController');

    Route::post('/attachements', 'API\UserController@profile')->name('attachments.store');

});
