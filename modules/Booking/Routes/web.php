<?php
use Illuminate\Support\Facades\Route;
// Booking
Route::group(['prefix'=>config('booking.booking_route_prefix')],function(){
    Route::post('/addToCart','BookingController@addToCart');
    Route::post('/doCheckout','BookingController@doCheckout')->name('booking.doCheckout');
    Route::get('/confirm/{gateway}','BookingController@confirmPayment');
    Route::get('/cancel/{gateway}','BookingController@cancelPayment');
    Route::get('/{code}','BookingController@detail');
    Route::get('/{code}/checkout','BookingController@checkout')->name('booking.checkout');
    Route::get('/{code}/check-status','BookingController@checkStatusCheckout');
    Route::get('/sepay/check-status/{code}','BookingController@checkSepayStatus')->name('sepay.check-status');

    //ical
	Route::get('/export-ical/{type}/{id}','BookingController@exportIcal')->name('booking.admin.export-ical');
    //inquiry
    Route::post('/addEnquiry','BookingController@addEnquiry');
    Route::post('/setPaidAmount','BookingController@setPaidAmount')->name('booking.setPaidAmount')->middleware(['auth','dashboard']);

    Route::get('/modal/{booking}','BookingController@modal')->name('booking.modal');
});


Route::group(['prefix'=>'gateway'],function(){
    Route::get('/confirm/{gateway}','NormalCheckoutController@confirmPayment')->name('gateway.confirm');
    Route::get('/cancel/{gateway}','NormalCheckoutController@cancelPayment')->name('gateway.cancel');
    Route::get('/info','NormalCheckoutController@showInfo')->name('gateway.info');
    Route::match(['get','post'],'/gateway_callback/{gateway}','BookingController@callbackPayment')->name('gateway.webhook');
});

Route::group(['prefix' => 'sepay', 'middleware' => ['web']], function() {
    Route::get('/oauth/connect', '\Modules\Booking\Controllers\SepayOAuthController@connect')->name('sepay.oauth.connect');
    Route::get('/oauth/callback', '\Modules\Booking\Controllers\SepayOAuthController@getCallback')->name('sepay.oauth.callback');
    Route::post('/oauth/callback', '\Modules\Booking\Controllers\SepayOAuthController@callback');
    Route::get('/oauth/disconnect', '\Modules\Booking\Controllers\SepayOAuthController@disconnect')->name('sepay.oauth.disconnect');
});

