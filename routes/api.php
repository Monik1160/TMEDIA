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


Route::group(['prefix' => 'v1'], function () {
    //Auth
    Route::post('login', 'Api\V1\Auth\AuthController@login');
    Route::post('password/email', 'Api\V1\Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Api\V1\Auth\ResetPasswordController@reset');
});

Route::group([
    'prefix' => 'v1',
], function () { // custom admin routes
    Route::get('logout', 'Api\V1\Auth\AuthController@logout');
}); // this should be the absolute last line of this file




Route::group(['prefix' => 'v1'], function () {
    //User Profile
    Route::get('user/profile', 'Api\V1\User\UserProfileController@getUserProfile');
    Route::put('user/profile', 'Api\V1\User\UserProfileController@updateUserProfile');

    //Tasks
    Route::get('task', 'Api\V1\Tarea\TareaController@tasksAssignedByInstaller');
    Route::post('task/start', 'Api\V1\Tarea\TareaController@startTaskByInstaller');
    Route::post('task/accept', 'Api\V1\Tarea\TareaController@acceptTasksByInstaller');
    Route::post('task/is_picked_up', 'Api\V1\Tarea\TareaController@isPickedUpTaskByInstaller');
    Route::post('task/decline', 'Api\V1\Tarea\TareaController@declineTaskByInstaller');
    Route::post('task/image', 'Api\V1\Tarea\TareaController@addImagesToTask');
    Route::post('task/image/delete', 'Api\V1\Tarea\TareaController@deleteImagesToTask');
    Route::post('task/images', 'Api\V1\Tarea\TareaController@addImagesToTask');
    Route::post('task/detail', 'Api\V1\Tarea\TareaController@getDetailTask');
    Route::post('task/cancel', 'Api\V1\Tarea\TareaController@cancelTaskWithReason');
    Route::post('task/remove', 'Api\V1\Tarea\TareaController@removeActiveTask');
    Route::post('task/comments/add', 'Api\V1\Tarea\TareaController@addCommentsToTaskByInstaller');
    Route::post('task/comments', 'Api\V1\Tarea\TareaController@getCommentsTask');
    Route::post('task/end', 'Api\V1\Tarea\TareaController@endTaskByInstaller');
    Route::any('task/history', 'Api\V1\Tarea\TareaController@getHistoryTaskByUser');
    Route::post('task/change-bus', 'Api\V1\Tarea\TareaController@changeBus');

    //User NotificationResource
    Route::get('user/notification', 'Api\V1\User\NotificationController@getNotifications');
    Route::post('user/notification/read', 'Api\V1\User\NotificationController@readNotification');
    Route::post('user/notification/unread', 'Api\V1\User\NotificationController@unreadNotification');
    Route::post('user/notification/archived', 'Api\V1\User\NotificationController@archivedNotification');

    Route::post('user/contact', 'Api\V1\Tarea\TareaController@sendContactForm');

    Route::get('privacy-policy', 'Api\V1\Tarea\TareaController@privacyPolicy');

});
