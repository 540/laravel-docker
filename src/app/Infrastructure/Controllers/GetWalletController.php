<?php

namespace App\Infrastructure\Controllers;

use App\Application\EarlyAdopter\GetWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\Debugbar\LaravelDebugbar;
use Illuminate\Http\Request;
use Exception;

class GetWalletController extends BaseController
{
    private GetWalletService $getWalletService;

    public function __construct(GetWalletService $walletService)
    {
        $this->getWalletService = $walletService;
    }

    public function __invoke(int $wallet_id) : JsonResponse
    {
        try {
            $wallet = $this->getWalletService->execute($wallet_id);
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json([
            $wallet->coinsToJson()
        ], Response::HTTP_OK);
    }

}
