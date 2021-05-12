<?php

namespace App\Http\Controllers;

use App\Services\WalletBalance\GetWalletBalanceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GetWalletBalanceController extends Controller
{
    private GetWalletBalanceService $getWalletBalanceService;

    public function __construct(GetWalletBalanceService $getWalletBalanceService)
    {
        $this->getWalletBalanceService = $getWalletBalanceService;
    }

    public function getWalletBalance($walletId): JsonResponse
    {
        try {
            $balance = $this->getWalletBalanceService->execute($walletId);
            return response()->json([
                'balance_usd' => $balance
            ], Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
