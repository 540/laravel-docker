<?php

use App\DataSource\API\CoinDataSource;
use App\DataSource\API\CoinLoreCoinDataSource;
use App\Http\Controllers\coinBuyerController;
use App\Http\Controllers\GetUserController;
use App\Http\Controllers\GetWalletBalanceController;
use App\Http\Controllers\GetWalletCryptocurrenciesController;
use App\Http\Controllers\OpenWalletController;
use App\Http\Controllers\SellCoinController;
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

app()->bind(CoinDataSource::class, CoinLoreCoinDataSource::class);

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

Route::get(
    '/wallet/{wallet_id}',
    [GetWalletCryptocurrenciesController::class, 'getWalletCryptocurrencies']
);

Route::get(
    '/wallet/{wallet_id}/balance',
    [GetWalletBalanceController::class, 'getWalletBalance']
);

Route::post(
    '/coin/buy',
    [CoinBuyerController::class, 'buyCoin']
);

Route::post(
    '/coin/sell',
    [SellCoinController::class, 'sellCoin']
);
