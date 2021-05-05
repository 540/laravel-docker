<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BuyCoinsController;
use App\Http\Controllers\GetWalletCryptocurrenciesController;
use App\Http\Controllers\IsEarlyAdopterController;
use App\Http\Controllers\OpenWalletController;
use App\Http\Controllers\SellCoinsController;
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

// EJEMPLO PARA OBTENER LOS DATOS DE UN USUARIO
Route::get(
    'user/{id}', //así se obtienen parametros por url
    IsEarlyAdopterController::class
);

Route::post(
    '/wallet/open',
    OpenWalletController::class
);

Route::get(
    '/wallet/{wallet_id}',
    GetWalletCryptocurrenciesController::class
);

Route::post(
    '/coin/buy',
    BuyCoinsController::class
);

Route::post(
    '/coin/sell',
    SellCoinsController::class
);

Route::get(
    '/wallet/{wallet_id}/balance',
    BalanceController::class
);
