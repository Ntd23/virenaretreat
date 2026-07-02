<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get(config('admin.admin_route_prefix').'/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view'])->name('admin.logs');

Route::group(['prefix' => config('admin.admin_route_prefix'), 'middleware' => ['auth', 'dashboard']], function () {
    Route::get('/advertisements', 'Admin\AdminAdvertisementRequestController@index')->name('admin.advertisements.index');
    Route::get('/advertisements/waiting-list', 'Admin\AdminAdvertisementRequestController@waitingList')->name('admin.advertisements.waiting-list');
    Route::get('/advertisements/running-list', 'Admin\AdminAdvertisementRequestController@runningList')->name('admin.advertisements.running-list');
    Route::get('/advertisements/pricing', 'Admin\AdminAdvertisementRequestController@pricing')->name('admin.advertisements.pricing');
    Route::post('/advertisements/pricing', 'Admin\AdminAdvertisementRequestController@updatePricing')->name('admin.advertisements.pricing.update');
    Route::get('/advertisements/{advertisementRequest}', 'Admin\AdminAdvertisementRequestController@show')->name('admin.advertisements.show');
    Route::post('/advertisements/{advertisementRequest}/approve', 'Admin\AdminAdvertisementRequestController@approve')->name('admin.advertisements.approve');
    Route::post('/advertisements/{advertisementRequest}/reject', 'Admin\AdminAdvertisementRequestController@reject')->name('admin.advertisements.reject');
    Route::post('/advertisements/{advertisementRequest}/confirm-payment', 'Admin\AdminAdvertisementRequestController@confirmPayment')->name('admin.advertisements.confirm-payment');
    Route::post('/advertisements/{advertisementRequest}/add-to-waiting-queue', 'Admin\AdminAdvertisementRequestController@addToWaitingQueue')->name('admin.advertisements.add-to-waiting-queue');
    Route::post('/advertisements/{advertisementRequest}/confirm-running', 'Admin\AdminAdvertisementRequestController@confirmRunning')->name('admin.advertisements.confirm-running');
    Route::post('/advertisements/{advertisementRequest}/complete', 'Admin\AdminAdvertisementRequestController@complete')->name('admin.advertisements.complete');
});

Route::get('/install','InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
Route::get('/install/environment','InstallerController@redirectToWizard')->name('LaravelInstaller::environment');
