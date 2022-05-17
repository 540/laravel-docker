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

    private $openNewWalletService;


    public function __construct(OpenNewWalletService $walletService)
    {
        $this->openNewWalletService = $walletService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try{
            $user_id = $request->input("user_id");
            $wallet_id = $this->openNewWalletService->execute($user_id);
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'wallet_id' => $wallet_id
        ], Response::HTTP_OK);
    }
}
