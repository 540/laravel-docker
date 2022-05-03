<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenNewWalletController extends BaseController
{
    public function __invoke(string $userId): JsonResponse
    {
        try {
            // Create new wallet
            // $wallet_id=>this
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            //'res' => $wallet_id
        ], Response::HTTP_OK);
    }
    }
}
