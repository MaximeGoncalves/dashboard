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
//    Login API
Route::post('/login', 'API\ApiAuthController@login');

Route::group(['middleware' => 'auth:api'], function () {

//    ANALYTICS
    Route::get('/analytics/getViews', 'API\AnalyticsController@getViews');
    Route::get('/analytics/getData', 'API\AnalyticsController@getData');
    Route::post('/analytics', 'API\AnalyticsController@store');

//    USER
    Route::get('/user/search', 'API\UserController@search');
    Route::get('/user/getAllUsers', 'API\UserController@getAllUsers');
    Route::apiResource('user', 'API\UserController');

//    SOCIETY
    Route::get('/society/search', 'API\SocietyController@search');
    Route::apiResource('society', 'API\SocietyController');

//    TICKET SOFT
    Route::post('/tickets/addAttachment/{ticket}', 'API\TicketController@addAttachmentTicket');
    Route::delete('/tickets/deleteAttachment/{attachment}', 'API\TicketController@deleteFile');
    Route::get('/tickets/stats', 'API\TicketController@stats');
    Route::put('/tickets/{ticket}/editDescription', 'API\TicketController@editDescription');
    Route::apiResource('tickets', 'API\TicketController');
    Route::post('/tickets/sendmail', 'API\TicketController@sendEmail');

//    LOGINS
    Route::get('/logins/search', 'API\LoginsController@search');
    Route::apiResource('logins', 'API\LoginsController');

//    PROFILE
    Route::get('/profile', 'API\UserController@profile');
    Route::put('/updateProfile', 'API\UserController@updateProfile');
    Route::post('/attachements', 'API\UserController@profile')->name('attachments.store');

//    ACTIONS
    Route::get('/actions', 'API\ActionsController@index');
    Route::post('/addAction/{ticket}', 'API\ActionsController@store');

//    MESSAGE
    Route::get('/message', 'API\MessageController@index');
    Route::post('/message/{ticket}', 'API\MessageController@store');
    Route::put('/message/{message}', 'API\MessageController@update');
    Route::post('/taskmessage/{message}', 'API\MessageController@taskstore');
    Route::delete('/message/delete/{message}', 'API\MessageController@destroy');
    Route::post('/message/sendEmailMessage', 'API\MessageController@sendEmailMessage'); // ne fonctionne pas encore ..
    //    NOTE
    Route::get('/note', 'API\NoteController@index');
    Route::post('/note/{ticket}', 'API\NoteController@store');
    Route::put('/note/{note}', 'API\NoteController@update');
    Route::delete('/note/delete/{note}', 'API\NoteController@destroy');

// TYPES
    Route::post('/type', 'API\TypeController@store');
    Route::delete('/type/{type}', 'API\TypeController@destroy');

//   ATTACHMENT
    Route::post('/attachment/{id}', 'API\AttachmentController@store');
    Route::delete('/attachment/{id}', 'API\AttachmentController@destroy');

//    KNOWLEDGES
    Route::apiResource('/knowledges', 'API\KnowledgesController');

//    LICENCE
    Route::apiResource('/licences', 'API\LicencesController');

//    TASKS
    Route::put('/tasks/{id}/editDescription', 'API\TaskController@updateDescription');
    Route::put('/tasks/{id}/updateList', 'API\TaskController@updateList');
    Route::put('/subtask/name/{id}', 'API\TaskController@updateNameSub');
    Route::put('/subtask/state/{id}', 'API\TaskController@updateCheckSub');
    Route::post('/subtask', 'API\TaskController@storeSub');
    Route::delete('/subtask/destroy/{id}', 'API\TaskController@destroySub');
    Route::put('/tasks/updateStateOfTask/{id}', 'API\TaskController@updateStateOfTask');
    Route::post('/attachmentsTask/{id}', 'API\TaskController@addAttachmentTask');
    Route::delete('/attachmentsTask/{id}', 'API\TaskController@deleteFile');
    Route::get('/tasks/getPercent/{id}', 'API\TaskController@getPercent');
    Route::post('/tasks/addUser/{id}','API\TaskController@addUserToTask' );
    Route::post('/tasks/addDueDate/{id}','API\TaskController@addDueDateToTask' );
    Route::post('/tasks/resendToBoard/{id}', 'API\TaskController@resendToBoard');
    Route::get('/tasks/archive', 'API\TaskController@getArchived');
    Route::delete('/tasks/archive/{id}', 'API\TaskController@archive');
    Route::apiResource('/tasks', 'API\TaskController');

    //Task Project
    Route::apiResource('/taskproject', 'API\TaskProjectController');

    //Task List
    Route::apiResource('/lists', 'API\ListController');
    Route::post('/lists/order', 'API\ListController@order');

//    board
    Route::apiResource('/board', 'API\BoardController');
    Route::post('/board/addMembers/{id}', 'API\BoardController@addMembers');
//    Notifications
    Route::get('/notifications', 'API\NotificationsController@index');
    Route::get('/notifications/read', 'API\NotificationsController@readNotification');




});
