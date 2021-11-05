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

// Health
use App\Http\Controllers\HealthCheckController;

Route::get('/_info', [HealthCheckController::class, 'info']);
Route::get('/_health', [HealthCheckController::class, 'health']);

Route::group(
    [
        'prefix' => config('backpack.base.route_prefix', 'admin'),
        'middleware' => ['web'],
        'namespace' => 'Admin',
    ],
    function () {

        // --------------------------
        //  setup_my_account_routes
        // --------------------------
        Route::get('edit-account-info', 'Auth\MyAccountController@getAccountInfoForm')->name('backpack.account.info');
        Route::post('edit-account-info', 'Auth\MyAccountController@postAccountInfoForm');

    });

Route::get('testing-jobs', 'TestingController@testJob');
