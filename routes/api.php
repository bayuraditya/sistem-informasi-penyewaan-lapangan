<?php

use App\Http\Controllers\ReservationController;
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

Route::post('/midtrans-callback', [ReservationController::class, 'callback']);
// Route::post('/midtrans-recurring', [ReservationController::class, 'recurring']);
// Route::post('/midtrans-pay-account-notification', [ReservationController::class, 'pay_account_notification']);
// Route::post('/midtrans-finish', [ReservationController::class, 'finish']);
// Route::post('/midtrans-unfinish', [ReservationController::class, 'unfinish']);


