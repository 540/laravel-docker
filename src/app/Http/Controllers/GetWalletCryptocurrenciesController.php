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

    public function getWalletCryptocurrencies($walletId):JsonResponse{
        try {
            $coins = $this->getWalletCryptoCurrenciesService->execute($walletId);
            return response()->json($coins, Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
