<?php

use App\Http\Controllers\GetUserController;
use App\Http\Controllers\OpenWalletController;
use App\Http\Controllers\SellCoinController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
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
    '/user/{email}',
    GetUserController::class
);

Route::post(
    '/wallet/open',
    [OpenWalletController::class, 'openWallet']
);

Route::post(
    '/coin/sell',
    [SellCoinController::class, 'sellCoin']
);
