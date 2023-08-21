<?php

use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\WalletApiController;
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

Route::post('/user', [UserApiController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(UserApiController::class)->group(function() {
        Route::get('/users/{id}', 'index');
    });

    Route::controller(WalletApiController::class)->group(function() {
        Route::post('/wallet', 'store');
        Route::get('/wallets/{id}', 'getAllByUser');
        Route::get('/wallet/{id}', 'findById');
    });

    Route::controller(TransactionApiController::class)->group(function() {
        Route::get('/transactions/{id}', 'getTransactionsByUser');
        Route::post('/send', 'sendMoney');
    });
});
