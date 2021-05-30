<?php

use App\Http\Controllers\GetWalletController;
use App\Http\Controllers\GetWalletBalanceController;
use App\Http\Controllers\PostWalletOpenController;
use App\Http\Controllers\PostCoinBuyController;
use App\Http\Controllers\PostCoinSellController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

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

Route::get(
    '/status',
    StatusController::class
);

Route::get(
    '/wallet/{wallet_id}',
    GetWalletController::class
);

Route::get(
    '/wallet/{wallet_id}/balance',
    GetWalletBalanceController::class
);

Route::post(
    '/wallet/open',
    PostWalletOpenController::class
);

Route::post(
    '/coin/buy',
    PostCoinBuyController::class
);

Route::post(
    '/coin/sell',
    PostCoinSellController::class
);
