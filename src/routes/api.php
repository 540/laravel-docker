<?php

use App\Http\Controllers\CoinController;
use App\Http\Controllers\IsEarlyAdopterUserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\WalletController;
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
    WalletController::class
);

Route::get(
    '/wallet/{wallet_id}/balance',
    [WalletController::class, 'balance']
);

Route::post(
    '/coin/buy',
    [CoinController::class, 'buy']
);

Route::post(
    '/coin/sell',
    [CoinController::class, 'sell']
);

Route::get( //DEMO IKER
    'user/{email}',
    IsEarlyAdopterUserController::class
);
