<?php

use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\TabbyWebhookController;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('send-notification', [NotificationController::class, 'sendNotification']);
Route::get('sms', [NotificationController::class, 'sms']);
Route::get('whatsApp', [NotificationController::class, 'whatsApp']);
Route::get('travels', [TravelController::class, 'index'])->name('travels.index');
Route::get('travels/{travel:slug}/tours', [TourController::class, 'index']);

Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('travels', [Admin\TravelController::class, 'store']);
        Route::post('travels/{travel}/tours', [Admin\TourController::class, 'store']);
    });
    Route::put('travels/{travel}', [Admin\TravelController::class, 'update']);
});

Route::post('login', LoginController::class);
Route::get('pay',  [PaymentController::class,'pay']);
Route::post('/webhook/tabby', [TabbyWebhookController::class, 'handleWebhook'])->name('webhook.tabby');
// Route to handle the webhook creation
Route::post('/create-tabby-webhook', [TabbyWebhookController::class, 'createWebhook']);
Route::get('/createMultipleWebhooks', [TabbyWebhookController::class, 'createMultipleWebhooks']);

