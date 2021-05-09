<?php

namespace App\Http\Controllers;

use App\Services\WalletCryptocurrencies\GetWalletCryptocurrenciesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GetWalletCryptocurrenciesController extends Controller
{
    private GetWalletCryptocurrenciesService $getWalletCryptoCurrenciesService;

    public function __construct(GetWalletCryptocurrenciesService $getWalletCryptoCurrenciesService)
    {
        $this->getWalletCryptoCurrenciesService = $getWalletCryptoCurrenciesService;
    }

    public function getWalletCryptocurrencies($user_id):JsonResponse{
        try {
            $this->getWalletCryptoCurrenciesService->execute($user_id);
            return response()->json([
                'coin_id' => 1,
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'amount' => 1,
                'value_usd' => 1
            ], Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json([
                'error' => 'a wallet with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
