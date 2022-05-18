<?php

namespace App\Infrastructure\Controllers;

use App\Application\EarlyAdopter\OpenNewWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class OpenNewWalletController extends BaseController
{

    private OpenNewWalletService $openNewWalletService;


    public function __construct(OpenNewWalletService $walletService)
    {
        $this->openNewWalletService = $walletService;
    }

    public function __invoke(): JsonResponse
    {
        try{
            $wallet = $this->openNewWalletService->execute();
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([
            'wallet_id' => $wallet->getWalletId()
        ], Response::HTTP_OK);
    }
}
