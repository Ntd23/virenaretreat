<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAdvertisementRequestController;
use App\Http\Controllers\Api\AdvertisementRequestController;
use App\Http\Controllers\Api\SepayWebhookController;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/sepay/webhook', SepayWebhookController::class)->name('api.sepay.webhook');

Route::middleware('auth:api')->group(function () {
    Route::get('/advertisement-requests', [AdvertisementRequestController::class, 'index']);
    Route::post('/advertisement-requests', [AdvertisementRequestController::class, 'store']);
    Route::get('/advertisement-requests/{advertisementRequest}', [AdvertisementRequestController::class, 'show']);

    Route::prefix('admin/advertisement-requests')->group(function () {
        Route::get('/', [AdminAdvertisementRequestController::class, 'index']);
        Route::get('/waiting-list', [AdminAdvertisementRequestController::class, 'waitingList']);
        Route::get('/running-list', [AdminAdvertisementRequestController::class, 'runningList']);
        Route::get('/{advertisementRequest}', [AdminAdvertisementRequestController::class, 'show']);
        Route::post('/{advertisementRequest}/approve', [AdminAdvertisementRequestController::class, 'approve']);
        Route::post('/{advertisementRequest}/reject', [AdminAdvertisementRequestController::class, 'reject']);
        Route::post('/{advertisementRequest}/confirm-payment', [AdminAdvertisementRequestController::class, 'confirmPayment']);
        Route::post('/{advertisementRequest}/add-to-waiting-queue', [AdminAdvertisementRequestController::class, 'addToWaitingQueue']);
        Route::post('/{advertisementRequest}/confirm-running', [AdminAdvertisementRequestController::class, 'confirmRunning']);
        Route::post('/{advertisementRequest}/complete', [AdminAdvertisementRequestController::class, 'complete']);
    });
});
